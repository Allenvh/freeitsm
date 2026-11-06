<?php
/**
 * Leases (3.5.0) - Assets -> Leases.
 *
 * A LEASE is the agreement (lessor, agreement number, cost centre, optional
 * contract, notice period). It has SCHEDULES - a group of kit with its own dates
 * and cost, because one agreement can cover 10 PCs from one date and 10 monitors
 * from another. Each schedule has ASSETS, and each asset on a schedule has an
 * OUTCOME (Assets -> Settings -> Leasing): 'active' kinds (On lease, Extended)
 * are still out; 'closed' kinds (Returned, Bought out) are finished with.
 *
 * DATES. An asset on a schedule takes the schedule's dates unless it has its own
 * (lease_assets.start_date / end_date - "we kept this one laptop six months
 * longer"). The asset's own lease_start / lease_expiry are KEPT IN STEP with the
 * effective dates, so everything that already reads them - the asset panel,
 * Assets -> Table, the dashboard's lease alerts, the calendar - stays right with
 * no second source of truth:
 *   - schedule or per-asset dates change  -> leaseSyncAsset() writes the asset
 *     through AssetsService::updateFields() (audited);
 *   - someone edits the dates on the asset -> AssetsService calls
 *     leaseAssetDatesEdited(), which records them as that asset's own dates.
 * Only an asset's ACTIVE row is kept in step. Once returned, its dates are left
 * as the record of what happened.
 *
 * COMPANY. leases.tenant_id is scoped data like assets: NULL = the Default
 * company. Its assets, contract and cost centre are all of the same company.
 * An asset is on at most ONE active lease row at a time (enforced here - the
 * database cannot express "unique among active rows").
 *
 * ⚠️ Every read goes through leaseVisibilitySql() / leaseListSql() /
 * leaseCanView(); a read that forgets shows one company's lease to another.
 */

require_once __DIR__ . '/tenancy.php';
require_once __DIR__ . '/service_context.php';
require_once __DIR__ . '/money.php';   // GH #189: currencies are checked against the registry

const LEASE_PERIODS = ['month', 'quarter', 'year', 'total'];

/** True once Database Verification has created the lease tables. */
function leaseReady(PDO $conn): bool
{
    static $ok = null;
    if ($ok !== null) return $ok;
    try { $conn->query("SELECT 1 FROM lease_assets LIMIT 0"); $conn->query("SELECT 1 FROM lease_outcomes LIMIT 0"); return $ok = true; }
    catch (Throwable) { return $ok = false; }
}

/**
 * " AND (...)" - leases in any company this analyst can reach. For links, the asset panel, documents.
 *
 * @param string $l  the leases table's alias in the calling query
 */
function leaseVisibilitySql(PDO $conn, int $analystId, string $l = 'l'): array
{
    if (!isMultiTenant($conn)) return ['', []];
    return allAccessibleTenantsFilter($conn, $analystId, "$l.tenant_id");
}

/**
 * The Leases list and Due back: follow the company switcher, like Assets.
 *
 * @param string $l  the leases table's alias in the calling query
 */
function leaseListSql(PDO $conn, int $analystId, string $l = 'l'): array
{
    if (!isMultiTenant($conn)) return ['', []];
    return activeTenantReadFilter($conn, $analystId, $l);
}

/**
 * True when the lease is in a company the analyst may read.
 *
 * @param int $leaseId  the leases id
 */
function leaseCanView(PDO $conn, int $analystId, int $leaseId): bool
{
    [$sql, $p] = leaseVisibilitySql($conn, $analystId, 'l');
    $s = $conn->prepare("SELECT 1 FROM leases l WHERE l.id = ?$sql");
    $s->execute(array_merge([$leaseId], $p));
    return (bool)$s->fetchColumn();
}

/**
 * A lease row the analyst may see, or a not_found ServiceError (never reveals another company's).
 *
 * @param int $leaseId  the leases id
 */
function leaseLoad(PDO $conn, int $analystId, int $leaseId): array
{
    [$sql, $p] = leaseVisibilitySql($conn, $analystId, 'l');
    $s = $conn->prepare("SELECT l.* FROM leases l WHERE l.id = ?$sql");
    $s->execute(array_merge([$leaseId], $p));
    $r = $s->fetch(PDO::FETCH_ASSOC);
    if (!$r) throw new ServiceError('not_found', 'not_found', 'Lease not found.');
    return $r;
}

/**
 * The lease's company as a real id (NULL read as Default).
 *
 * @param array $lease  the leases row, as from leaseLoad()
 */
function leaseTenant(PDO $conn, array $lease): int
{
    return $lease['tenant_id'] === null ? getDefaultTenantId($conn) : (int)$lease['tenant_id'];
}

/**
 * A schedule and its lease, both visible to the analyst.
 *
 * @param int $scheduleId  the lease_schedules id
 * @return array{0:array,1:array} [schedule, lease]
 */
function leaseLoadSchedule(PDO $conn, int $analystId, int $scheduleId): array
{
    $s = $conn->prepare("SELECT * FROM lease_schedules WHERE id = ?");
    $s->execute([$scheduleId]);
    $sch = $s->fetch(PDO::FETCH_ASSOC);
    if (!$sch) throw new ServiceError('not_found', 'not_found', 'Schedule not found.');
    return [$sch, leaseLoad($conn, $analystId, (int)$sch['lease_id'])];
}

/**
 * SQL: this lease_assets row (alias $la, outcome alias $lo) is still out on lease.
 *
 * @param string $la  the lease_assets alias in the calling query
 * @param string $lo  the lease_outcomes alias (LEFT JOINed on la.outcome_id) in the calling query
 */
function leaseActiveSql(string $la = 'la', string $lo = 'lo'): string
{
    return "($la.outcome_id IS NULL OR $lo.kind = 'active')";
}

/**
 * SQL: the asset (alias $a) has GONE BACK - it is on a lease, and none of its
 * lease rows is still out (every one is Returned, Bought out, ...).
 *
 * TRAP: a returned asset keeps lease_expiry as the record of what happened (see
 * the header), so anything that alerts on lease_expiry must skip these, or a
 * machine handed back in March is "due back" for ever. Call only when leaseReady().
 *
 * @param string $a  the alias the calling query gives the assets table
 */
function leaseGoneBackSql(string $a = 'a'): string
{
    return "(EXISTS (SELECT 1 FROM lease_assets gb1 WHERE gb1.asset_id = $a.id)
             AND NOT EXISTS (SELECT 1 FROM lease_assets gb2
                             LEFT JOIN lease_outcomes gbo ON gbo.id = gb2.outcome_id
                             WHERE gb2.asset_id = $a.id AND " . leaseActiveSql('gb2', 'gbo') . "))";
}

/**
 * Outcomes, in order. $activeOnly drops switched-off ones (still shown where in use).
 *
 * @param bool $activeOnly  true to leave out outcomes that have been switched off
 */
function leaseOutcomes(PDO $conn, bool $activeOnly = false): array
{
    if (!leaseReady($conn)) return [];
    $rows = $conn->query("SELECT id, name, kind, is_default, display_order, is_active FROM lease_outcomes"
        . ($activeOnly ? " WHERE is_active = 1" : '') . " ORDER BY display_order, name")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as &$r) { $r['id'] = (int)$r['id']; $r['is_default'] = (int)$r['is_default']; $r['is_active'] = (int)$r['is_active']; $r['display_order'] = (int)$r['display_order']; }
    return $rows;
}

/** The outcome a newly added asset starts with: the default, else the first active kind. */
function leaseDefaultOutcomeId(PDO $conn): ?int
{
    $id = $conn->query("SELECT id FROM lease_outcomes WHERE is_active = 1 AND kind = 'active'
                         ORDER BY is_default DESC, display_order, id LIMIT 1")->fetchColumn();
    return $id ? (int)$id : null;
}

/**
 * Validates an optional YYYY-MM-DD date; blank gives null, anything else invalid throws a
 * validation error naming $label.
 *
 * @param mixed $v  untrusted input; null or '' gives null, otherwise only the first 10 characters are checked
 * @param string $label  the field's name, used in the validation message
 */
function leaseDate($v, string $label): ?string
{
    if ($v === null || $v === '') return null;
    $v = substr(trim((string)$v), 0, 10);
    $d = DateTime::createFromFormat('!Y-m-d', $v);
    if (!$d || $d->format('Y-m-d') !== $v) {
        throw new ServiceError('validation', 'invalid_field', "$label is not a valid date (YYYY-MM-DD).");
    }
    return $v;
}

/**
 * Trims optional text and cuts it to $max characters; blank gives null.
 *
 * @param mixed $v  untrusted input; null or blank after trimming gives null
 * @param int $max  the most characters kept
 */
function leaseText($v, int $max): ?string
{
    $v = trim((string)($v ?? ''));
    return $v === '' ? null : mb_substr($v, 0, $max);
}

// ============================================================================
// Leases
// ============================================================================

/**
 * Create or update a lease. $in: id?, name, reference, supplier_id, cost_centre_id,
 * contract_id, notice_days, notes, company_id (create; or a move while it has no assets).
 *
 * @param array $in  the posted fields: id (0 or absent creates), name, reference, supplier_id, cost_centre_id, contract_id,
 *        notice_days, notes, company_id; a key left out keeps the current value
 */
function leaseSave(PDO $conn, ActorContext $ctx, array $in): int
{
    $aid = $ctx->actorId;
    $id = (int)($in['id'] ?? 0);
    $cur = $id ? leaseLoad($conn, $aid, $id) : null;

    $name = leaseText($in['name'] ?? ($cur['name'] ?? ''), 200);
    if ($name === null) throw new ServiceError('validation', 'missing_field', 'A lease needs a name.');

    // ---- company ----
    $tenant = $cur ? leaseTenant($conn, $cur) : getActiveTenantId($conn, $aid);
    if (isset($in['company_id']) && (int)$in['company_id'] > 0) {
        $want = (int)$in['company_id'];
        if (isMultiTenant($conn) && !analystCanAccessTenant($conn, $aid, $want)) {
            throw new ServiceError('validation', 'invalid_field', 'You do not have access to that company.');
        }
        if ($cur && $want !== $tenant) {
            $n = $conn->prepare("SELECT COUNT(*) FROM lease_assets la JOIN lease_schedules s ON s.id = la.schedule_id WHERE s.lease_id = ?");
            $n->execute([$id]);
            if ((int)$n->fetchColumn() > 0) {
                throw new ServiceError('validation', 'invalid_field', 'This lease has assets on it, so it cannot move to another company. Take them off first.');
            }
        }
        $tenant = $want;
    }
    $companyChanged = $cur && $tenant !== leaseTenant($conn, $cur);
    $store = $tenant === getDefaultTenantId($conn) ? null : $tenant;

    // ---- supplier (the shared list) ----
    $supplier = array_key_exists('supplier_id', $in) ? ((int)$in['supplier_id'] ?: null) : ($cur['supplier_id'] ?? null);
    if ($supplier !== null) {
        $s = $conn->prepare("SELECT id FROM suppliers WHERE id = ?");
        $s->execute([$supplier]);
        if (!$s->fetchColumn()) throw new ServiceError('validation', 'invalid_field', 'Unknown supplier.');
    }

    // ---- cost centre: an active one of the lease's company (GH #160 rules) ----
    require_once __DIR__ . '/cost_centre_assign.php';
    $curCc = ($cur && !$companyChanged && $cur['cost_centre_id'] !== null) ? (int)$cur['cost_centre_id'] : null;
    if (array_key_exists('cost_centre_id', $in)) {
        $cc = costCentreCheck($conn, $tenant, $in['cost_centre_id'], $curCc);
    } else {
        $cc = $cur ? ($companyChanged ? null : ($cur['cost_centre_id'] !== null ? (int)$cur['cost_centre_id'] : null)) : null;
        if ($cur && $companyChanged && $cur['cost_centre_id'] !== null) {
            throw new ServiceError('validation', 'invalid_field', 'Choose one of the new company\'s cost centres, or none.');
        }
    }

    // ---- contract: optional, visible, same company ----
    $contract = array_key_exists('contract_id', $in) ? ((int)$in['contract_id'] ?: null) : ($cur['contract_id'] ?? null);
    if ($contract !== null) {
        require_once __DIR__ . '/contract_party.php';
        $ct = contractTenantOf($conn, (int)$contract);
        if ($ct === null || !contractCanView($conn, $aid, (int)$contract)) {
            throw new ServiceError('validation', 'invalid_field', 'Contract not found.');
        }
        if (isMultiTenant($conn) && $ct !== $tenant) {
            throw new ServiceError('validation', 'invalid_field', 'The contract belongs to another company. Link one of this lease\'s company.');
        }
    }

    $notice = array_key_exists('notice_days', $in) ? $in['notice_days'] : ($cur['notice_days'] ?? null);
    if ($notice === '' || $notice === null) {
        $notice = null;
    } else {
        if (!is_numeric($notice) || (int)$notice != $notice || (int)$notice < 0 || (int)$notice > 3650) {
            throw new ServiceError('validation', 'invalid_field', 'Notice period is a number of days (0 to 3650).');
        }
        $notice = (int)$notice;
    }

    $ref   = array_key_exists('reference', $in) ? leaseText($in['reference'], 100) : ($cur['reference'] ?? null);
    $notes = array_key_exists('notes', $in) ? leaseText($in['notes'], 20000) : ($cur['notes'] ?? null);

    if ($cur) {
        $conn->prepare("UPDATE leases SET tenant_id = ?, name = ?, reference = ?, supplier_id = ?, cost_centre_id = ?, contract_id = ?,
                               notice_days = ?, notes = ?, updated_datetime = UTC_TIMESTAMP() WHERE id = ?")
             ->execute([$store, $name, $ref, $supplier, $cc, $contract, $notice, $notes, $id]);
        if ((string)$cur['notice_days'] !== (string)$notice) leaseSyncCalendar($conn);
        return $id;
    }
    $conn->prepare("INSERT INTO leases (tenant_id, name, reference, supplier_id, cost_centre_id, contract_id, notice_days, notes, created_by, created_datetime, updated_datetime)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, UTC_TIMESTAMP(), UTC_TIMESTAMP())")
         ->execute([$store, $name, $ref, $supplier, $cc, $contract, $notice, $notes, $aid ?: null]);
    $newId = (int)$conn->lastInsertId();
    // A lease starts with one schedule - most leases only ever need one.
    $conn->prepare("INSERT INTO lease_schedules (lease_id, name, display_order) VALUES (?, ?, 1)")->execute([$newId, 'Schedule 1']);
    return $newId;
}

/**
 * Delete a lease, its schedules and its asset rows. The assets themselves keep
 * the lease dates they were given - deleting a record of the agreement does not
 * rewrite what the asset says happened.
 *
 * @param int $id  the leases id
 */
function leaseDelete(PDO $conn, ActorContext $ctx, int $id): void
{
    leaseLoad($conn, $ctx->actorId, $id);
    $conn->prepare("DELETE FROM leases WHERE id = ?")->execute([$id]);   // schedules + asset rows cascade
    try {
        require_once __DIR__ . '/documents.php';
        if (function_exists('documentsDetachParent')) documentsDetachParent($conn, 'lease', $id);
    } catch (Throwable) { /* the orphan sweep catches it */ }
    leaseSyncCalendar($conn);
}

/**
 * The Leases list: one row per lease with its schedule dates, asset counts and next end.
 *
 * @param string $search  text matched (LIKE) against the lease name, reference and supplier names; '' for all
 */
function leaseList(PDO $conn, int $analystId, string $search = ''): array
{
    [$sql, $p] = leaseListSql($conn, $analystId, 'l');
    $where = '1=1' . $sql;
    if ($search !== '') {
        $where .= " AND (l.name LIKE ? OR l.reference LIKE ? OR su.trading_name LIKE ? OR su.legal_name LIKE ?)";
        $like = '%' . $search . '%';
        array_push($p, $like, $like, $like, $like);
    }
    $act = leaseActiveSql();
    $s = $conn->prepare(
        "SELECT l.id, l.name, l.reference, l.tenant_id, l.notice_days,
                COALESCE(NULLIF(su.trading_name, ''), su.legal_name) AS supplier_name,
                t.name AS company_name,
                (SELECT COUNT(*) FROM lease_schedules s WHERE s.lease_id = l.id) AS schedule_count,
                (SELECT MIN(s.start_date) FROM lease_schedules s WHERE s.lease_id = l.id) AS start_date,
                (SELECT MAX(s.end_date) FROM lease_schedules s WHERE s.lease_id = l.id) AS end_date,
                (SELECT COUNT(*) FROM lease_assets la JOIN lease_schedules s ON s.id = la.schedule_id
                  LEFT JOIN lease_outcomes lo ON lo.id = la.outcome_id WHERE s.lease_id = l.id AND $act) AS active_assets,
                (SELECT COUNT(*) FROM lease_assets la JOIN lease_schedules s ON s.id = la.schedule_id WHERE s.lease_id = l.id) AS total_assets,
                (SELECT MIN(COALESCE(la.end_date, s.end_date)) FROM lease_assets la JOIN lease_schedules s ON s.id = la.schedule_id
                  LEFT JOIN lease_outcomes lo ON lo.id = la.outcome_id WHERE s.lease_id = l.id AND $act) AS next_end
           FROM leases l
      LEFT JOIN suppliers su ON su.id = l.supplier_id
      LEFT JOIN tenants t ON t.id = COALESCE(l.tenant_id, ?)
          WHERE $where
       ORDER BY (next_end IS NULL), next_end, l.name"
    );
    $s->execute(array_merge([getDefaultTenantId($conn)], $p));
    $rows = $s->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as &$r) {
        foreach (['id', 'schedule_count', 'active_assets', 'total_assets'] as $k) $r[$k] = (int)$r[$k];
        $r['notice_days'] = $r['notice_days'] === null ? null : (int)$r['notice_days'];
    }
    return $rows;
}

/**
 * One lease with its schedules and every asset on them.
 *
 * @param int $id  the leases id
 */
function leaseGet(PDO $conn, int $analystId, int $id): array
{
    $l = leaseLoad($conn, $analystId, $id);
    $tenant = leaseTenant($conn, $l);
    $out = [
        'id' => (int)$l['id'], 'name' => $l['name'], 'reference' => $l['reference'],
        'company_id' => $tenant, 'company_name' => getTenantById($conn, $tenant)['name'] ?? '',
        'supplier_id' => $l['supplier_id'] !== null ? (int)$l['supplier_id'] : null, 'supplier_name' => null,
        'cost_centre_id' => $l['cost_centre_id'] !== null ? (int)$l['cost_centre_id'] : null, 'cost_centre_label' => null,
        'contract_id' => null, 'contract_label' => null,
        'notice_days' => $l['notice_days'] !== null ? (int)$l['notice_days'] : null,
        'notes' => $l['notes'], 'created_datetime' => $l['created_datetime'],
    ];
    if ($l['supplier_id']) {
        $s = $conn->prepare("SELECT COALESCE(NULLIF(trading_name, ''), legal_name) FROM suppliers WHERE id = ?");
        $s->execute([(int)$l['supplier_id']]);
        $out['supplier_name'] = $s->fetchColumn() ?: null;
    }
    if ($l['cost_centre_id']) {
        require_once __DIR__ . '/cost_centre_assign.php';
        $out['cost_centre_label'] = costCentreLabel(costCentreRef($conn, (int)$l['cost_centre_id']));
    }
    if ($l['contract_id']) {
        // Shown only to an analyst who may see the contract; otherwise it is as if unlinked.
        require_once __DIR__ . '/contract_party.php';
        if (contractCanView($conn, $analystId, (int)$l['contract_id'])) {
            $s = $conn->prepare("SELECT contract_number, title FROM contracts WHERE id = ?");
            $s->execute([(int)$l['contract_id']]);
            if ($c = $s->fetch(PDO::FETCH_ASSOC)) {
                $out['contract_id'] = (int)$l['contract_id'];
                $out['contract_label'] = trim(($c['contract_number'] ? $c['contract_number'] . ' - ' : '') . $c['title']);
            }
        }
    }

    $sch = $conn->prepare("SELECT * FROM lease_schedules WHERE lease_id = ? ORDER BY display_order, id");
    $sch->execute([$id]);
    $schedules = [];
    foreach ($sch->fetchAll(PDO::FETCH_ASSOC) as $s) {
        $s['id'] = (int)$s['id'];
        $s['cost'] = $s['cost'] !== null ? (float)$s['cost'] : null;
        $s['notice_date'] = ($s['end_date'] && $out['notice_days'] !== null)
            ? date('Y-m-d', strtotime($s['end_date'] . ' -' . $out['notice_days'] . ' days')) : null;
        $s['assets'] = [];
        $schedules[$s['id']] = $s;
    }
    if ($schedules) {
        $in = implode(',', array_keys($schedules));
        $rows = $conn->query(
            "SELECT la.id, la.schedule_id, la.asset_id, la.outcome_id, la.start_date AS own_start, la.end_date AS own_end,
                    la.outcome_date, la.notes, a.hostname, a.asset_tag, a.manufacturer, a.model, a.service_tag,
                    lo.name AS outcome_name, lo.kind AS outcome_kind,
                    (SELECT GROUP_CONCAT(COALESCE(NULLIF(TRIM(u.display_name), ''), u.username) SEPARATOR ', ')
                       FROM users_assets ua JOIN users u ON u.id = ua.user_id WHERE ua.asset_id = a.id) AS held_by
               FROM lease_assets la
               JOIN assets a ON a.id = la.asset_id
          LEFT JOIN lease_outcomes lo ON lo.id = la.outcome_id
              WHERE la.schedule_id IN ($in)
           ORDER BY a.hostname, a.id"
        )->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $r) {
            $s = $schedules[(int)$r['schedule_id']];
            $r['id'] = (int)$r['id']; $r['asset_id'] = (int)$r['asset_id']; $r['schedule_id'] = (int)$r['schedule_id'];
            $r['outcome_id'] = $r['outcome_id'] !== null ? (int)$r['outcome_id'] : null;
            $r['active'] = $r['outcome_id'] === null || $r['outcome_kind'] === 'active';
            $r['start_date'] = $r['own_start'] ?: $s['start_date'];
            $r['end_date'] = $r['own_end'] ?: $s['end_date'];
            $schedules[(int)$r['schedule_id']]['assets'][] = $r;
        }
    }
    $out['schedules'] = array_values($schedules);
    return $out;
}

// ============================================================================
// Schedules
// ============================================================================

/**
 * Create or update a schedule. $in: id? | lease_id, name, reference, start_date, end_date, cost, cost_period, currency, order_number, notes.
 *
 * @param array $in  the posted fields: id (update) or lease_id (create), name, reference, start_date, end_date, cost,
 *        cost_period, currency, order_number, notes; a key left out keeps the current value
 */
function leaseScheduleSave(PDO $conn, ActorContext $ctx, array $in): int
{
    $aid = $ctx->actorId;
    $id = (int)($in['id'] ?? 0);
    if ($id) {
        [$cur, $lease] = leaseLoadSchedule($conn, $aid, $id);
    } else {
        $cur = null;
        $lease = leaseLoad($conn, $aid, (int)($in['lease_id'] ?? 0));
    }
    $pick = function (string $k) use ($in, $cur) { return array_key_exists($k, $in) ? $in[$k] : ($cur[$k] ?? null); };

    $start = leaseDate($pick('start_date'), 'Start date');
    $end   = leaseDate($pick('end_date'), 'End date');
    if ($start && $end && $start > $end) throw new ServiceError('validation', 'invalid_field', 'The schedule cannot end before it starts.');

    $cost = $pick('cost');
    if ($cost === '' || $cost === null) {
        $cost = null;
    } else {
        $cost = str_replace([',', ' '], '', (string)$cost);
        if (!is_numeric($cost) || (float)$cost < 0) throw new ServiceError('validation', 'invalid_field', 'Cost is a number.');
        $cost = round((float)$cost, 2);
    }
    $period = (string)($pick('cost_period') ?: 'month');
    if (!in_array($period, LEASE_PERIODS, true)) throw new ServiceError('validation', 'invalid_field', 'Cost period is month, quarter, year or total.');
    $currency = moneyServiceCurrency($pick('currency'));

    // Per-asset dates must still sit the right way round against the new schedule dates.
    if ($cur) {
        $chk = $conn->prepare("SELECT COUNT(*) FROM lease_assets WHERE schedule_id = ? AND COALESCE(start_date, ?) > COALESCE(end_date, ?)");
        $chk->execute([$id, $start ?? '0000-01-01', $end ?? '9999-12-31']);
        if ((int)$chk->fetchColumn() > 0) {
            throw new ServiceError('validation', 'invalid_field', 'An asset on this schedule would end before it starts with these dates. Change that asset\'s own dates first.');
        }
    }

    $vals = [leaseText($pick('name'), 200), leaseText($pick('reference'), 100), $start, $end, $cost, $period, $currency,
             leaseText($pick('order_number'), 100), leaseText($pick('notes'), 20000)];
    if ($cur) {
        $conn->prepare("UPDATE lease_schedules SET name = ?, reference = ?, start_date = ?, end_date = ?, cost = ?, cost_period = ?,
                               currency = ?, order_number = ?, notes = ? WHERE id = ?")->execute(array_merge($vals, [$id]));
        if ($cur['start_date'] !== $start || $cur['end_date'] !== $end) {
            leaseSyncSchedule($conn, $ctx, $id);
            leaseSyncCalendar($conn);
        }
        return $id;
    }
    $order = $conn->prepare("SELECT COALESCE(MAX(display_order), 0) + 1 FROM lease_schedules WHERE lease_id = ?");
    $order->execute([(int)$lease['id']]);
    if ($vals[0] === null) {
        $n = $conn->prepare("SELECT COUNT(*) + 1 FROM lease_schedules WHERE lease_id = ?");
        $n->execute([(int)$lease['id']]);
        $vals[0] = 'Schedule ' . (int)$n->fetchColumn();
    }
    $conn->prepare("INSERT INTO lease_schedules (lease_id, name, reference, start_date, end_date, cost, cost_period, currency, order_number, notes, display_order)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")
         ->execute(array_merge([(int)$lease['id']], $vals, [(int)$order->fetchColumn()]));
    $newId = (int)$conn->lastInsertId();   // before the calendar sync inserts its own rows
    leaseSyncCalendar($conn);
    return $newId;
}

/**
 * Delete a schedule and its asset rows (the assets keep their dates). The last schedule cannot go.
 *
 * @param int $id  the lease_schedules id
 */
function leaseScheduleDelete(PDO $conn, ActorContext $ctx, int $id): void
{
    [, $lease] = leaseLoadSchedule($conn, $ctx->actorId, $id);
    $n = $conn->prepare("SELECT COUNT(*) FROM lease_schedules WHERE lease_id = ?");
    $n->execute([(int)$lease['id']]);
    if ((int)$n->fetchColumn() <= 1) {
        throw new ServiceError('validation', 'invalid_field', 'A lease needs at least one schedule. Delete the lease instead.');
    }
    $conn->prepare("DELETE FROM lease_schedules WHERE id = ?")->execute([$id]);
    leaseSyncCalendar($conn);
}

// ============================================================================
// Assets on a schedule
// ============================================================================

/**
 * Put assets on a schedule. Each must be in the lease's company, visible to the
 * actor, and not already out on another lease. An asset that was on this
 * schedule before and was returned is put back on it.
 *
 * @param int $scheduleId  the lease_schedules id to put the assets on
 * @param array $assetIds  the asset ids to add (cast to int, duplicates and 0 ignored)
 * @return array{added:int, refused:array<int,string>} refused: asset id => reason
 */
function leaseAssetsAdd(PDO $conn, ActorContext $ctx, int $scheduleId, array $assetIds): array
{
    $aid = $ctx->actorId;
    [, $lease] = leaseLoadSchedule($conn, $aid, $scheduleId);
    $tenant = leaseTenant($conn, $lease);
    $default = getDefaultTenantId($conn);
    $outcome = leaseDefaultOutcomeId($conn);
    $act = leaseActiveSql();
    $added = 0; $refused = [];

    foreach (array_unique(array_map('intval', $assetIds)) as $assetId) {
        if ($assetId <= 0) continue;
        $a = $conn->prepare("SELECT id, hostname, tenant_id FROM assets WHERE id = ?");
        $a->execute([$assetId]);
        $row = $a->fetch(PDO::FETCH_ASSOC);
        if (!$row || ($aid > 0 && !analystCanAccessAsset($conn, $aid, $assetId))) { $refused[$assetId] = 'Asset not found.'; continue; }
        $at = $row['tenant_id'] === null ? $default : (int)$row['tenant_id'];
        if (isMultiTenant($conn) && $at !== $tenant) { $refused[$assetId] = 'It belongs to another company.'; continue; }

        $on = $conn->prepare("SELECT l.name, la.schedule_id FROM lease_assets la
                                JOIN lease_schedules s ON s.id = la.schedule_id JOIN leases l ON l.id = s.lease_id
                           LEFT JOIN lease_outcomes lo ON lo.id = la.outcome_id
                               WHERE la.asset_id = ? AND $act LIMIT 1");
        $on->execute([$assetId]);
        if ($o = $on->fetch(PDO::FETCH_ASSOC)) {
            if ((int)$o['schedule_id'] !== $scheduleId) $refused[$assetId] = 'It is already on lease ' . $o['name'] . '.';
            continue;   // already on this one: nothing to do
        }

        $conn->prepare("INSERT INTO lease_assets (schedule_id, asset_id, outcome_id, created_by, created_datetime, updated_datetime)
                        VALUES (?, ?, ?, ?, UTC_TIMESTAMP(), UTC_TIMESTAMP())
                        ON DUPLICATE KEY UPDATE outcome_id = VALUES(outcome_id), outcome_date = NULL, start_date = NULL, end_date = NULL,
                                                updated_datetime = UTC_TIMESTAMP()")
             ->execute([$scheduleId, $assetId, $outcome, $aid ?: null]);
        leaseSyncAsset($conn, $ctx, $assetId);
        $added++;
    }
    if ($added) leaseSyncCalendar($conn);
    return ['added' => $added, 'refused' => $refused];
}

/**
 * Load a lease_assets row the actor may touch.
 *
 * @param int $id  the lease_assets row id (not the asset id)
 * @return array{0:array,1:array,2:array} [row, schedule, lease]
 */
function leaseLoadAssetRow(PDO $conn, int $analystId, int $id): array
{
    $s = $conn->prepare("SELECT * FROM lease_assets WHERE id = ?");
    $s->execute([$id]);
    $r = $s->fetch(PDO::FETCH_ASSOC);
    if (!$r) throw new ServiceError('not_found', 'not_found', 'Not found.');
    [$sch, $lease] = leaseLoadSchedule($conn, $analystId, (int)$r['schedule_id']);
    return [$r, $sch, $lease];
}

/**
 * Change one asset on a schedule: outcome_id, outcome_date, start_date / end_date
 * (its own dates - blank = the schedule's), notes. Moving it to a CLOSED outcome
 * stamps today as the outcome date if none is given.
 *
 * @param int $id  the lease_assets row id (not the asset id)
 * @param array $in  any of outcome_id, outcome_date, start_date, end_date, notes; a key left out keeps the current value
 */
function leaseAssetUpdate(PDO $conn, ActorContext $ctx, int $id, array $in): void
{
    [$r, $sch] = leaseLoadAssetRow($conn, $ctx->actorId, $id);
    $pick = function (string $k) use ($in, $r) { return array_key_exists($k, $in) ? $in[$k] : $r[$k]; };

    $outcomeId = $pick('outcome_id');
    $outcomeId = ($outcomeId === '' || $outcomeId === null) ? null : (int)$outcomeId;
    $kind = 'active';
    if ($outcomeId !== null) {
        $o = $conn->prepare("SELECT kind, is_active FROM lease_outcomes WHERE id = ?");
        $o->execute([$outcomeId]);
        $orow = $o->fetch(PDO::FETCH_ASSOC);
        if (!$orow || (!$orow['is_active'] && $outcomeId !== ($r['outcome_id'] !== null ? (int)$r['outcome_id'] : null))) {
            throw new ServiceError('validation', 'invalid_field', 'Unknown outcome.');
        }
        $kind = $orow['kind'];
    }
    $start = leaseDate($pick('start_date'), 'Start date');
    $end   = leaseDate($pick('end_date'), 'End date');
    // Its own date equal to the schedule's is no own date at all.
    if ($start === $sch['start_date']) $start = null;
    if ($end === $sch['end_date']) $end = null;
    $effS = $start ?? $sch['start_date']; $effE = $end ?? $sch['end_date'];
    if ($effS && $effE && $effS > $effE) throw new ServiceError('validation', 'invalid_field', 'The lease cannot end before it starts.');

    $outDate = leaseDate($pick('outcome_date'), 'Outcome date');
    if ($kind === 'closed' && $outDate === null) $outDate = date('Y-m-d');
    if ($kind === 'active') $outDate = null;

    // Going back on lease: only if it is not out on another one meanwhile.
    $wasActive = $r['outcome_id'] === null || $conn->query("SELECT kind FROM lease_outcomes WHERE id = " . (int)$r['outcome_id'])->fetchColumn() === 'active';
    if (!$wasActive && $kind === 'active') {
        $act = leaseActiveSql();
        $on = $conn->prepare("SELECT 1 FROM lease_assets la LEFT JOIN lease_outcomes lo ON lo.id = la.outcome_id WHERE la.asset_id = ? AND la.id <> ? AND $act");
        $on->execute([(int)$r['asset_id'], $id]);
        if ($on->fetchColumn()) throw new ServiceError('conflict', 'conflict', 'This asset is out on another lease now.');
    }

    $conn->prepare("UPDATE lease_assets SET outcome_id = ?, start_date = ?, end_date = ?, outcome_date = ?, notes = ?, updated_datetime = UTC_TIMESTAMP() WHERE id = ?")
         ->execute([$outcomeId, $start, $end, $outDate, leaseText($pick('notes'), 500), $id]);
    if ($kind === 'active') leaseSyncAsset($conn, $ctx, (int)$r['asset_id']);
    // The full sync, not just the notice entries: a machine just returned must lose its "Lease ends" entry too.
    leaseSyncAllCalendar($conn);
}

/**
 * Take an asset off a schedule altogether (a mistake, not a return - use an outcome for that).
 *
 * @param int $id  the lease_assets row id (not the asset id)
 */
function leaseAssetRemove(PDO $conn, ActorContext $ctx, int $id): void
{
    leaseLoadAssetRow($conn, $ctx->actorId, $id);
    $conn->prepare("DELETE FROM lease_assets WHERE id = ?")->execute([$id]);
    leaseSyncAllCalendar($conn);
}

// ============================================================================
// Keeping the asset's own dates in step
// ============================================================================

/** The asset's ACTIVE lease row with its effective dates, or null. */
function leaseActiveRowForAsset(PDO $conn, int $assetId): ?array
{
    if (!leaseReady($conn)) return null;
    $act = leaseActiveSql();
    $s = $conn->prepare("SELECT la.id, la.start_date AS own_start, la.end_date AS own_end, s.start_date AS sch_start, s.end_date AS sch_end,
                                COALESCE(la.start_date, s.start_date) AS start_date, COALESCE(la.end_date, s.end_date) AS end_date
                           FROM lease_assets la JOIN lease_schedules s ON s.id = la.schedule_id
                      LEFT JOIN lease_outcomes lo ON lo.id = la.outcome_id
                          WHERE la.asset_id = ? AND $act ORDER BY la.id DESC LIMIT 1");
    $s->execute([$assetId]);
    return $s->fetch(PDO::FETCH_ASSOC) ?: null;
}

/** Write the effective dates onto the asset (audited, through AssetsService). */
function leaseSyncAsset(PDO $conn, ActorContext $ctx, int $assetId): void
{
    $row = leaseActiveRowForAsset($conn, $assetId);
    if (!$row) return;
    require_once __DIR__ . '/services/assets.php';
    // A system actor: the lease is what decides here, and the actor was already
    // checked against the lease and the asset by the caller.
    AssetsService::updateFields($conn, new ActorContext($ctx->actorId, null, $ctx->source, $ctx->locale, $ctx->actorName),
        $assetId, ['lease_start' => $row['start_date'], 'lease_expiry' => $row['end_date']]);
}

/**
 * Every active asset on a schedule, after its dates change.
 *
 * @param int $scheduleId  the lease_schedules id whose dates changed
 */
function leaseSyncSchedule(PDO $conn, ActorContext $ctx, int $scheduleId): void
{
    $act = leaseActiveSql();
    $s = $conn->prepare("SELECT la.asset_id FROM lease_assets la LEFT JOIN lease_outcomes lo ON lo.id = la.outcome_id WHERE la.schedule_id = ? AND $act");
    $s->execute([$scheduleId]);
    foreach ($s->fetchAll(PDO::FETCH_COLUMN) as $assetId) leaseSyncAsset($conn, $ctx, (int)$assetId);
}

/**
 * Called by AssetsService after an asset's lease_start / lease_expiry change.
 * On an asset that is out on lease, the asset's dates become its OWN dates on
 * the schedule (blank when they match the schedule). Never writes the asset,
 * so it cannot loop with leaseSyncAsset().
 */
function leaseAssetDatesEdited(PDO $conn, int $assetId): void
{
    $row = leaseActiveRowForAsset($conn, $assetId);
    if (!$row) return;
    $a = $conn->prepare("SELECT lease_start, lease_expiry FROM assets WHERE id = ?");
    $a->execute([$assetId]);
    $asset = $a->fetch(PDO::FETCH_ASSOC);
    if (!$asset) return;
    $start = $asset['lease_start'] === $row['sch_start'] ? null : $asset['lease_start'];
    $end   = $asset['lease_expiry'] === $row['sch_end'] ? null : $asset['lease_expiry'];
    if ($start !== $row['own_start'] || $end !== $row['own_end']) {
        $conn->prepare("UPDATE lease_assets SET start_date = ?, end_date = ?, updated_datetime = UTC_TIMESTAMP() WHERE id = ?")
             ->execute([$start, $end, (int)$row['id']]);
        leaseSyncCalendar($conn);
    }
}

/** For the asset panel: every lease this asset has been on, newest first. */
function leaseHistoryForAsset(PDO $conn, int $analystId, int $assetId): array
{
    if (!leaseReady($conn)) return [];
    [$sql, $p] = leaseVisibilitySql($conn, $analystId, 'l');
    $s = $conn->prepare("SELECT l.id AS lease_id, l.name AS lease_name, l.reference, s.name AS schedule_name,
                                COALESCE(la.start_date, s.start_date) AS start_date, COALESCE(la.end_date, s.end_date) AS end_date,
                                la.start_date IS NOT NULL OR la.end_date IS NOT NULL AS own_dates,
                                lo.name AS outcome_name, " . leaseActiveSql() . " AS active, la.outcome_date
                           FROM lease_assets la JOIN lease_schedules s ON s.id = la.schedule_id JOIN leases l ON l.id = s.lease_id
                      LEFT JOIN lease_outcomes lo ON lo.id = la.outcome_id
                          WHERE la.asset_id = ?$sql ORDER BY active DESC, la.id DESC");
    $s->execute(array_merge([$assetId], $p));
    $rows = $s->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as &$r) { $r['lease_id'] = (int)$r['lease_id']; $r['active'] = (bool)$r['active']; $r['own_dates'] = (bool)$r['own_dates']; }
    return $rows;
}

// ============================================================================
// Due back
// ============================================================================

/**
 * Assets still out on lease whose (effective) end falls in a window: $days ahead
 * of today, overdue ones always included. $days 0 = everything still out.
 *
 * @param int $days  how many days ahead of today to look; overdue rows are always included, 0 = everything still out
 * @param int $leaseId  limit to one lease; 0 for every lease
 */
function leaseDueBack(PDO $conn, int $analystId, int $days = 90, int $leaseId = 0): array
{
    [$sql, $p] = leaseListSql($conn, $analystId, 'l');
    $where = leaseActiveSql() . $sql;
    if ($days > 0) {
        $where .= " AND COALESCE(la.end_date, s.end_date) IS NOT NULL AND COALESCE(la.end_date, s.end_date) <= ?";
        $p[] = date('Y-m-d', strtotime("+$days days"));
    }
    if ($leaseId > 0) { $where .= " AND l.id = ?"; $p[] = $leaseId; }
    $s = $conn->prepare(
        "SELECT la.id, la.asset_id, a.hostname, a.asset_tag, a.manufacturer, a.model, a.service_tag,
                l.id AS lease_id, l.name AS lease_name, l.reference, l.notice_days, s.id AS schedule_id, s.name AS schedule_name,
                COALESCE(la.start_date, s.start_date) AS start_date, COALESCE(la.end_date, s.end_date) AS end_date,
                la.end_date IS NOT NULL AS own_end, lo.name AS outcome_name, t.name AS company_name,
                COALESCE(NULLIF(su.trading_name, ''), su.legal_name) AS supplier_name,
                al.name AS location_name,
                (SELECT GROUP_CONCAT(COALESCE(NULLIF(TRIM(u.display_name), ''), u.username) SEPARATOR ', ')
                   FROM users_assets ua JOIN users u ON u.id = ua.user_id WHERE ua.asset_id = a.id) AS held_by
           FROM lease_assets la
           JOIN lease_schedules s ON s.id = la.schedule_id
           JOIN leases l ON l.id = s.lease_id
           JOIN assets a ON a.id = la.asset_id
      LEFT JOIN lease_outcomes lo ON lo.id = la.outcome_id
      LEFT JOIN suppliers su ON su.id = l.supplier_id
      LEFT JOIN asset_locations al ON al.id = a.location_id
      LEFT JOIN tenants t ON t.id = COALESCE(l.tenant_id, ?)
          WHERE $where
       ORDER BY (COALESCE(la.end_date, s.end_date) IS NULL), COALESCE(la.end_date, s.end_date), l.name, a.hostname"
    );
    $s->execute(array_merge([getDefaultTenantId($conn)], $p));
    $rows = $s->fetchAll(PDO::FETCH_ASSOC);
    $today = date('Y-m-d');
    foreach ($rows as &$r) {
        foreach (['id', 'asset_id', 'lease_id', 'schedule_id'] as $k) $r[$k] = (int)$r[$k];
        $r['own_end'] = (bool)$r['own_end'];
        $r['notice_date'] = ($r['end_date'] && $r['notice_days'] !== null)
            ? date('Y-m-d', strtotime($r['end_date'] . ' -' . (int)$r['notice_days'] . ' days')) : null;
        $r['overdue'] = $r['end_date'] !== null && $r['end_date'] < $today;
    }
    return $rows;
}

// ============================================================================
// Calendar: "Give notice" entries
// ============================================================================

/**
 * One calendar entry per schedule on the day notice must be given (its end date
 * minus the lease's notice period), for schedules with something still out on
 * them. Follows the same Assets -> Settings switch as the lease end entries
 * (asset_lease_surface: calendar / both) and sits in the same category.
 */
/** Every asset-date calendar entry (warranty, "Lease ends", lease notice) - it calls leaseSyncCalendar() itself. */
function leaseSyncAllCalendar(PDO $conn): void
{
    try {
        require_once __DIR__ . '/asset_warranty_calendar.php';
        syncAssetWarrantyCalendar($conn);
    } catch (Throwable) { /* the calendar is never worth failing a lease edit over */ }
}

/**
 * Rebuilds the "Lease notice" calendar entries - the last day to give notice on each
 * schedule that still has kit out.
 *
 * Deletes every entry from source 'asset_lease_notice' and, when the lease alert surface
 * includes the calendar, adds one all-day event per schedule. Never throws, so a calendar
 * problem cannot fail a lease save.
 *
 * @writes calendar_events - one all-day entry per schedule, on end date minus notice days
 */
function leaseSyncCalendar(PDO $conn): void
{
    try {
        require_once __DIR__ . '/asset_warranty_calendar.php';
        if (!leaseReady($conn) || !awcColumnExists($conn, 'calendar_events', 'source')) return;
        if (!moduleIsEnabled($conn, 'calendar')) return;   // GH #217: nothing at all while the Calendar is switched off
        $source = 'asset_lease_notice';
        $conn->prepare("DELETE FROM calendar_events WHERE source = ?")->execute([$source]);
        if (!in_array(awcGetSetting($conn, 'asset_lease_surface', 'dashboard'), ['calendar', 'both'], true)) return;
        $act = leaseActiveSql();
        $rows = $conn->query(
            "SELECT l.name AS lease_name, l.reference, s.name AS schedule_name, s.end_date, l.notice_days,
                    (SELECT COUNT(*) FROM lease_schedules s2 WHERE s2.lease_id = l.id) AS schedule_count
               FROM lease_schedules s JOIN leases l ON l.id = s.lease_id
              WHERE s.end_date IS NOT NULL AND l.notice_days IS NOT NULL
                AND EXISTS (SELECT 1 FROM lease_assets la LEFT JOIN lease_outcomes lo ON lo.id = la.outcome_id WHERE la.schedule_id = s.id AND $act)"
        )->fetchAll(PDO::FETCH_ASSOC);
        if (!$rows) return;
        // The lease end entries' category, so both live together.
        $cat = awcEnsureCategory($conn, 'asset_lease', 'Lease', '#8b5cf6');
        $ins = $conn->prepare("INSERT INTO calendar_events (title, description, category_id, start_datetime, end_datetime, all_day, created_by, source)
                               VALUES (?, ?, ?, ?, ?, 1, 0, ?)");
        foreach ($rows as $r) {
            $dt = date('Y-m-d', strtotime($r['end_date'] . ' -' . (int)$r['notice_days'] . ' days')) . ' 00:00:00';
            $title = 'Lease notice: ' . $r['lease_name'] . ((int)$r['schedule_count'] > 1 && $r['schedule_name'] ? ' - ' . $r['schedule_name'] : '');
            $desc = 'Last day to give notice on this lease (it ends ' . $r['end_date'] . ', notice ' . (int)$r['notice_days'] . ' days). '
                  . 'Auto-generated from Assets -> Leases.';
            $ins->execute([$title, $desc, $cat, $dt, $dt, $source]);
        }
    } catch (Throwable) {
        // A calendar that could not be rebuilt must never fail the lease save.
    }
}

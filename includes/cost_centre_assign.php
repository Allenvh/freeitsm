<?php
/**
 * Charging things to a cost centre (GH #160, stage 2) - the rules, ONCE.
 *
 * Assets, contracts and people (users) each carry cost_centre_id. Every one of
 * them belongs to a company (NULL = the Default company), and a cost centre
 * belongs to exactly one company (cost_centres.tenant_id, never NULL). So:
 *
 *   🔑 A record can only be charged to a cost centre of ITS OWN company.
 *   🔑 Only an ACTIVE one can be chosen - inactive means "not for new
 *      assignments" (Daniel's words in #160). A record already charged to one
 *      that has since been switched off KEEPS it, and may be saved unchanged.
 *
 * The screens, the REST API, the asset import and the contract-text migration
 * all come through costCentreCheck() / costCentreByCode(), so the rule cannot
 * differ between them. Pickers list costCentreOptions(), which is the same set
 * plus the record's current one when that is inactive - so it never vanishes
 * from the box it is shown in.
 *
 * Deleting a cost centre is refused while anything is charged to it - see
 * costCentreUsage() and CostCentresService::delete(). Retire, don't delete.
 */

require_once __DIR__ . '/tenancy.php';
require_once __DIR__ . '/service_context.php';

/**
 * Has Database Verification added cost_centre_id to $table yet?
 *
 * @param string $table  the table to probe, e.g. 'assets' (trusted - put straight into SQL)
 */
function costCentreReady(PDO $conn, string $table): bool
{
    static $ok = [];
    if (isset($ok[$table])) return $ok[$table];
    try { $conn->query("SELECT cost_centre_id FROM `$table` LIMIT 0"); return $ok[$table] = true; }
    catch (Throwable) { return $ok[$table] = false; }
}

/**
 * A record's company for cost centre purposes: its tenant_id, or Default when NULL.
 *
 * @param mixed $tenantId  the record's raw tenant_id column value; null, '' or 0 mean the Default company
 */
function costCentreRecordTenant(PDO $conn, $tenantId): int
{
    return ($tenantId === null || $tenantId === '' || (int)$tenantId <= 0) ? getDefaultTenantId($conn) : (int)$tenantId;
}

/**
 * What a picker offers for a record in company $tenantId: its active cost
 * centres, plus $keepId when that is the record's current one and inactive.
 *
 * @param ?int $keepId  the record's current cost_centres.id, listed even when inactive; null for none
 * @return array<int,array{id:int,code:string,name:string,is_active:bool}>
 */
function costCentreOptions(PDO $conn, int $tenantId, ?int $keepId = null): array
{
    try {
        $st = $conn->prepare("SELECT id, code, name, is_active FROM cost_centres
                               WHERE tenant_id = ? AND (is_active = 1 OR id = ?) ORDER BY code");
        $st->execute([$tenantId, $keepId ?? 0]);
    } catch (Throwable) {
        return [];   // before Database Verification
    }
    return array_map(fn($r) => ['id' => (int)$r['id'], 'code' => $r['code'], 'name' => $r['name'], 'is_active' => (bool)$r['is_active']],
                     $st->fetchAll(PDO::FETCH_ASSOC));
}

/**
 * Validate a cost centre being given to a record in company $tenantId.
 * Returns the id to store (null = none). Throws ServiceError otherwise.
 * $currentId is what the record holds now: keeping an inactive one is fine.
 *
 * @param mixed $value  the submitted cost centre id (untrusted); null, '', 0 or '0' mean none
 * @param ?int $currentId  the cost_centres.id the record holds now; null for a new record or none
 */
function costCentreCheck(PDO $conn, int $tenantId, $value, ?int $currentId = null): ?int
{
    if ($value === null || $value === '' || $value === 0 || $value === '0') return null;
    if (!is_numeric($value) || (int)$value <= 0) {
        throw new ServiceError('validation', 'invalid_field', "'cost_centre_id' must be a cost centre id.");
    }
    $id = (int)$value;
    $st = $conn->prepare("SELECT tenant_id, is_active, code FROM cost_centres WHERE id = ?");
    $st->execute([$id]);
    $row = $st->fetch(PDO::FETCH_ASSOC);
    // One in another company reads as unknown, like one that does not exist.
    if (!$row || (int)$row['tenant_id'] !== $tenantId) {
        throw new ServiceError('validation', 'invalid_field', "Unknown cost centre id: {$id}");
    }
    if (!(int)$row['is_active'] && $id !== $currentId) {
        throw new ServiceError('validation', 'invalid_field', "Cost centre {$row['code']} is inactive, so it cannot be chosen for anything new.");
    }
    return $id;
}

/**
 * A cost centre of company $tenantId by its CODE (ignoring case), or null.
 *
 * @param string $code  the cost centre code to find, e.g. from an import; trimmed, empty gives null
 */
function costCentreByCode(PDO $conn, int $tenantId, string $code): ?array
{
    $code = trim($code);
    if ($code === '') return null;
    $st = $conn->prepare("SELECT id, code, name, is_active FROM cost_centres WHERE tenant_id = ? AND code = ?");
    $st->execute([$tenantId, $code]);
    $r = $st->fetch(PDO::FETCH_ASSOC);
    return $r ? ['id' => (int)$r['id'], 'code' => $r['code'], 'name' => $r['name'], 'is_active' => (bool)$r['is_active']] : null;
}

/**
 * {id, code, name} for display and the API, or null.
 *
 * @param mixed $id  a cost_centres.id as stored on a record; null or 0 gives null
 */
function costCentreRef(PDO $conn, $id): ?array
{
    if ($id === null || (int)$id <= 0) return null;
    $st = $conn->prepare("SELECT id, code, name, is_active FROM cost_centres WHERE id = ?");
    $st->execute([(int)$id]);
    $r = $st->fetch(PDO::FETCH_ASSOC);
    return $r ? ['id' => (int)$r['id'], 'code' => $r['code'], 'name' => $r['name'], 'is_active' => (bool)$r['is_active']] : null;
}

/**
 * Columns for any SELECT over a table with cost_centre_id, alias $a:
 * cost_centre_id and cost_centre_label ("CODE Name"). NULLs before Verification.
 *
 * @param string $table  the table being selected from, e.g. 'assets' - used to check the column exists
 * @param string $a  the alias the calling query gives that table (trusted - put straight into SQL)
 */
function costCentreSelectSql(PDO $conn, string $table, string $a): string
{
    return costCentreReady($conn, $table)
        ? ", $a.cost_centre_id, (SELECT CONCAT(ccl.code, ' ', ccl.name) FROM cost_centres ccl WHERE ccl.id = $a.cost_centre_id) AS cost_centre_label"
        : ", NULL AS cost_centre_id, NULL AS cost_centre_label";
}

/**
 * "CODE Name", or '' - one display form everywhere.
 *
 * @param ?array $ref  a cost centre as costCentreRef() returns it; null gives ''
 */
function costCentreLabel(?array $ref): string
{
    return $ref ? trim($ref['code'] . ' ' . $ref['name']) : '';
}

/**
 * How many things are charged to cost centre $id, per kind, leaving out the
 * zeros. Empty means it is free to delete.
 *
 * @param int $id  cost_centres.id to count usage of
 * @return array<string,int> e.g. ['asset(s)' => 3, 'person/people' => 1]
 */
function costCentreUsage(PDO $conn, int $id): array
{
    $out = [];
    foreach ([
        'asset(s)'       => "SELECT COUNT(*) FROM assets WHERE cost_centre_id = ?",
        'contract(s)'    => "SELECT COUNT(*) FROM contracts WHERE cost_centre_id = ?",
        'person/people'  => "SELECT COUNT(*) FROM users WHERE cost_centre_id = ?",
        'project budget line(s)' => "SELECT COUNT(*) FROM project_budget_lines WHERE cost_centre_id = ?",
        // GH #188. Leases had the column (and a foreign key) but were not counted,
        // so deleting a cost centre a lease used got past this check.
        'lease(s)'       => "SELECT COUNT(*) FROM leases WHERE cost_centre_id = ?",
        'domain(s)'      => "SELECT COUNT(*) FROM domains WHERE cost_centre_id = ?",
    ] as $what => $sql) {
        try {
            $st = $conn->prepare($sql);
            $st->execute([$id]);
            $n = (int)$st->fetchColumn();
            if ($n > 0) $out[$what] = $n;
        } catch (Throwable) { /* not there before Database Verification */ }
    }
    return $out;
}

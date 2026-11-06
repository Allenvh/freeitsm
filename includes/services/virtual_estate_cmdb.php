<?php
/**
 * Source-bound CIs (GH #199 Part B): a connection's discovered clusters, hosts, VMs and
 * cluster roles kept in step with the CMDB - after a preview, and never overwriting what
 * people typed.
 *
 * THE BINDING IS THE IDENTITY. cmdb_object_sources ties a CI to one discovered object (a
 * virt_* row id) - one live binding per CI and per object, one row per PERIOD, closed never
 * deleted, with who or what started and ended it: the same shape as the asset <-> CI link
 * (GH #201, asset_ci_links). A rename, a live migration, a failover or a reviewed rebinding
 * changes the object, not the binding, so the same CI is kept.
 *
 * WHAT THE SYNC MAY CHANGE, and nothing else:
 *   - create a CI for an unbound object (in the connection's company, of YOUR mapped class)
 *   - the CI's NAME, if the connection says names follow the source
 *   - the relationships it created itself (cmdb_relationship_sources): member of, runs on
 *     (a separate type for highly available VMs, because only those can fail over - GH #199
 *     Part C), role in. Relationships, never containment.
 * It never touches owners, properties, or a relationship a person drew. Discovered facts
 * (state, host, CPU...) are SHOWN on the CI from the virtual estate (forCi()), never copied.
 *
 * WHAT IT NEVER DECIDES ALONE (virt_cmdb_decisions, and the preview):
 *   - a same-name CI of the class already exists: Bind to it, or Create a new one - a name is
 *     not proof
 *   - an object waiting on an identity review (VirtualEstateService) - nothing until decided
 *   - an object whose CI a person deleted - never recreated until someone says so
 *
 * TRAP: a person who deletes a relationship the sync owns will see it come back on the next
 *   report - discovery is the authority for its own edges. Unbind the object to stop that.
 *
 * @wiki Hyper-V-and-Cluster-Discovery-Design
 * @wiki Hyper-V-Discovery-Developer-Guide
 */
require_once dirname(__DIR__) . '/service_context.php';
require_once dirname(__DIR__) . '/tenancy.php';
require_once __DIR__ . '/cmdb.php';

final class VirtualEstateCmdbService
{
    /** Kind => its virt_* table. */
    public const KINDS = ['cluster' => 'virt_clusters', 'host' => 'virt_hosts', 'role' => 'virt_cluster_roles', 'vm' => 'virt_vms'];

    /** Cluster groups that are not roles in their own right: VM roles are the VMs; the rest are the cluster's own plumbing. */
    private const NOT_ROLES = ['VirtualMachine', 'Cluster', 'AvailableStorage'];

    public static function ready(PDO $conn): bool
    {
        static $ready = null;
        if ($ready === null) {
            try { $conn->query('SELECT 1 FROM cmdb_object_sources LIMIT 0'); $ready = true; }
            catch (PDOException) { $ready = false; }
        }
        return $ready;
    }

    // ======================================================================
    //  The mapping
    // ======================================================================

    /** The connection's mapping, or the empty (switched-off) one. */
    public static function mapping(PDO $conn, int $connectionId): array
    {
        $st = $conn->prepare('SELECT * FROM virt_cmdb_mappings WHERE connection_id = ?');
        $st->execute([$connectionId]);
        $m = $st->fetch(PDO::FETCH_ASSOC) ?: ['connection_id' => $connectionId, 'enabled' => 0, 'sync_names' => 1];
        $out = ['connection_id' => $connectionId, 'enabled' => (bool)$m['enabled'], 'sync_names' => (bool)($m['sync_names'] ?? 1),
            'last_sync_at' => $m['last_sync_at'] ?? null, 'last_sync_summary' => $m['last_sync_summary'] ?? null];
        foreach (['cluster_class_id', 'host_class_id', 'vm_class_id', 'role_class_id', 'member_type_id', 'runs_on_ha_type_id', 'runs_on_type_id', 'role_in_type_id'] as $k) {
            $out[$k] = isset($m[$k]) && $m[$k] !== null ? (int)$m[$k] : null;
        }
        return $out;
    }

    /**
     * Save the mapping. Classes and relationship types must exist and be active. A class
     * whose required properties discovery cannot fill is accepted but reported, because
     * creating its CIs will fail until the property gets a default or stops being required.
     */
    public static function saveMapping(PDO $conn, ActorContext $ctx, int $connectionId, array $in): array
    {
        $vals = [];
        foreach (['cluster_class_id', 'host_class_id', 'vm_class_id', 'role_class_id'] as $k) {
            $vals[$k] = !empty($in[$k]) ? (int)$in[$k] : null;
            if ($vals[$k] !== null && !self::exists($conn, 'cmdb_classes', $vals[$k])) {
                throw new ServiceError('validation', 'invalid_field', 'That CMDB class does not exist or is not active.');
            }
        }
        foreach (['member_type_id', 'runs_on_ha_type_id', 'runs_on_type_id', 'role_in_type_id'] as $k) {
            $vals[$k] = !empty($in[$k]) ? (int)$in[$k] : null;
            if ($vals[$k] !== null && !self::exists($conn, 'cmdb_relationship_types', $vals[$k])) {
                throw new ServiceError('validation', 'invalid_field', 'That relationship type does not exist or is not active.');
            }
        }
        $enabled = !empty($in['enabled']) ? 1 : 0;
        $syncNames = array_key_exists('sync_names', $in) ? (!empty($in['sync_names']) ? 1 : 0) : 1;
        $cols = array_keys($vals);
        $conn->prepare('INSERT INTO virt_cmdb_mappings (connection_id, enabled, sync_names, ' . implode(', ', $cols) . ', updated_by_id, updated_at)
            VALUES (?, ?, ?, ' . implode(', ', array_fill(0, count($cols), '?')) . ', ?, UTC_TIMESTAMP())
            ON DUPLICATE KEY UPDATE enabled = VALUES(enabled), sync_names = VALUES(sync_names), '
            . implode(', ', array_map(fn($c) => "$c = VALUES($c)", $cols)) . ', updated_by_id = VALUES(updated_by_id), updated_at = VALUES(updated_at)')
            ->execute(array_merge([$connectionId, $enabled, $syncNames], array_values($vals), [$ctx->actorId > 0 ? $ctx->actorId : null]));
        return self::mapping($conn, $connectionId);
    }

    /**
     * The suggested classes and relationship types, made if they do not exist (matched by
     * name or verb first, so an install's own are used). Returns their ids to fill the form -
     * nothing is mapped until the person saves.
     */
    public static function suggest(PDO $conn): array
    {
        $class = function (string $key, string $name) use ($conn): int {
            $st = $conn->prepare('SELECT id FROM cmdb_classes WHERE is_active = 1 AND (class_key = ? OR LOWER(name) = LOWER(?)) ORDER BY class_key = ? DESC LIMIT 1');
            $st->execute([$key, $name, $key]);
            if ($id = $st->fetchColumn()) { return (int)$id; }
            $k = $key; $n = 2;
            $chk = $conn->prepare('SELECT 1 FROM cmdb_classes WHERE class_key = ?');
            while (true) { $chk->execute([$k]); if (!$chk->fetchColumn()) { break; } $k = $key . '_' . $n++; }
            $conn->prepare('INSERT INTO cmdb_classes (class_key, name, description, display_order, is_active, created_datetime, updated_datetime)
                VALUES (?, ?, ?, 0, 1, UTC_TIMESTAMP(), UTC_TIMESTAMP())')->execute([$k, $name, 'Made for Hyper-V discovery (GH #199).']);
            return (int)$conn->lastInsertId();
        };
        $type = function (string $verb, string $inverse, string $direction, string $redundancy) use ($conn): int {
            // TRAP: a type is reused only when it BEHAVES the same - verb, impact direction and
            // redundancy. A "runs on" made by hand with impact from_to would otherwise be picked,
            // and a failed host would then grade nothing above it.
            $st = $conn->prepare('SELECT id FROM cmdb_relationship_types WHERE LOWER(verb) = LOWER(?) AND impact_direction = ? AND redundancy = ? AND COALESCE(is_active, 1) = 1 LIMIT 1');
            $st->execute([$verb, $direction, $redundancy]);
            if ($id = $st->fetchColumn()) { return (int)$id; }
            // One of that name that behaves differently: a distinct name, so the two can be told apart.
            $st = $conn->prepare('SELECT 1 FROM cmdb_relationship_types WHERE LOWER(verb) = LOWER(?) LIMIT 1');
            $st->execute([$verb]);
            if ($st->fetchColumn()) { $verb .= ' (discovered)'; $inverse .= ' (discovered)'; }
            $st = $conn->prepare('SELECT id FROM cmdb_relationship_types WHERE LOWER(verb) = LOWER(?) AND impact_direction = ? AND redundancy = ? AND COALESCE(is_active, 1) = 1 LIMIT 1');
            $st->execute([$verb, $direction, $redundancy]);
            if ($id = $st->fetchColumn()) { return (int)$id; }
            $conn->prepare('INSERT INTO cmdb_relationship_types (verb, inverse_verb, description, impact_direction, redundancy, display_order, is_active, created_datetime)
                VALUES (?, ?, ?, ?, ?, 0, 1, UTC_TIMESTAMP())')->execute([$verb, $inverse, 'Made for Hyper-V discovery (GH #199).', $direction, $redundancy]);
            return (int)$conn->lastInsertId();
        };
        return [
            'cluster_class_id'   => $class('cluster', 'Cluster'),
            'host_class_id'      => $class('hypervisor_host', 'Hypervisor host'),
            'vm_class_id'        => $class('virtual_machine', 'Virtual machine'),
            'role_class_id'      => $class('cluster_role', 'Cluster role'),
            'member_type_id'     => $type('is a member of', 'has member', 'from_to', 'membership'),
            'runs_on_ha_type_id' => $type('runs on (highly available)', 'hosts (highly available)', 'to_from', 'placement'),
            'runs_on_type_id'    => $type('runs on', 'hosts', 'to_from', 'none'),
            'role_in_type_id'    => $type('is a role in', 'has role', 'to_from', 'none'),
        ];
    }

    // ======================================================================
    //  Preview and decisions
    // ======================================================================

    /**
     * What the sync would do, without doing it: per kind, the objects already bound, those
     * it would create a CI for, possible matches to confirm, and those it is waiting on a
     * person for. Plus a warning for any mapped class whose required properties discovery
     * cannot fill.
     */
    public static function preview(PDO $conn, int $connectionId, int $limit = 300): array
    {
        $m = self::mapping($conn, $connectionId);
        $c = VirtualEstateService::connection($conn, $connectionId);
        $out = ['mapping' => $m, 'kinds' => [], 'warnings' => []];
        foreach (self::KINDS as $kind => $table) {
            $classId = $m[$kind . '_class_id'];
            if (!$classId) { continue; }
            $required = CmdbService::requiredProperties($conn, $classId);
            if ($required) {
                $out['warnings'][] = ['kind' => $kind, 'code' => 'required_properties', 'properties' => array_column($required, 'label')];
            }
            $rows = ['bound' => [], 'create' => [], 'match' => [], 'waiting' => [], 'left' => [], 'ci_deleted' => []];
            foreach (self::objects($conn, $connectionId, $kind) as $o) {
                $s = self::status($conn, $c, $m, $kind, $o);
                $rows[$s['status']][] = ['id' => (int)$o['id'], 'name' => $o['name']] + array_diff_key($s, ['status' => 1]);
            }
            $out['kinds'][$kind] = ['counts' => array_map('count', $rows), 'items' => array_map(fn($r) => array_slice($r, 0, $limit), $rows)];
        }
        return $out;
    }

    /**
     * A person's decision on one discovered object.
     *
     * @param string $action  bind (to $ciId, one of its possible matches or any unbound CI of
     *                        the mapped class in the company) | create_new | leave | reset
     *                        (forget a decision, e.g. recreate a deleted CI) | unbind
     */
    public static function decide(PDO $conn, ActorContext $ctx, int $connectionId, string $kind, int $virtId, string $action, ?int $ciId = null): array
    {
        if (!isset(self::KINDS[$kind])) { throw new ServiceError('validation', 'invalid_field', 'Unknown kind.'); }
        $c = VirtualEstateService::connection($conn, $connectionId);
        $m = self::mapping($conn, $connectionId);
        $o = self::object($conn, $kind, $virtId, $connectionId);
        $by = $ctx->actorId > 0 ? $ctx->actorId : null;
        $conn->beginTransaction();
        try {
            switch ($action) {
                case 'bind':
                    if (self::liveBinding($conn, $kind, $virtId)) { throw new ServiceError('conflict', 'already_bound', 'This object already has a CI.'); }
                    $ci = self::loadCi($conn, (int)$ciId, true);
                    if ((int)$ci['class_id'] !== (int)$m[$kind . '_class_id']) {
                        throw new ServiceError('validation', 'invalid_field', 'That CI is not of the class this kind is mapped to.');
                    }
                    if (self::tenantId($conn, $ci['tenant_id']) !== self::tenantId($conn, $c['tenant_id'])) {
                        throw new ServiceError('validation', 'different_company', 'That CI belongs to another company.');
                    }
                    if (self::liveBindingForCi($conn, (int)$ci['id'])) { throw new ServiceError('conflict', 'ci_already_bound', 'That CI is already bound to another discovered object.'); }
                    self::bind($conn, (int)$ci['id'], $connectionId, $kind, $virtId, $by, 'ui');
                    self::forget($conn, $kind, $virtId);
                    break;
                case 'create_new':
                case 'leave':
                    self::remember($conn, $connectionId, $kind, $virtId, $action === 'leave' ? 'leave_unbound' : 'create_new', $by);
                    break;
                case 'reset':
                    self::forget($conn, $kind, $virtId);
                    break;
                case 'unbind':
                    $b = self::liveBinding($conn, $kind, $virtId);
                    if (!$b) { throw new ServiceError('not_found', 'not_bound', 'This object has no CI.'); }
                    self::end($conn, $b, 'unbound', $by, 'ui');
                    self::remember($conn, $connectionId, $kind, $virtId, 'leave_unbound', $by);
                    break;
                default:
                    throw new ServiceError('validation', 'invalid_field', 'Unknown action.');
            }
            $conn->commit();
        } catch (Throwable $e) {
            if ($conn->inTransaction()) { $conn->rollBack(); }
            throw $e;
        }
        return ['kind' => $kind, 'id' => $virtId, 'name' => $o['name'], 'action' => $action];
    }

    // ======================================================================
    //  The sync
    // ======================================================================

    /**
     * Keep the CMDB in step with the connection's estate, if its mapping is switched on.
     * Returns a summary, or null when it is off. One transaction; the CIs' workflow events
     * fire after the commit.
     */
    public static function sync(PDO $conn, int $connectionId): ?array
    {
        if (!self::ready($conn)) { return null; }
        $m = self::mapping($conn, $connectionId);
        if (!$m['enabled']) { return null; }
        $c = VirtualEstateService::connection($conn, $connectionId);
        $actor = ActorContext::system('Hyper-V: ' . $c['name']);
        $source = mb_substr('integration:hyperv:' . $c['name'], 0, 80);
        $tenant = self::tenantId($conn, $c['tenant_id']);
        $sum = ['created' => 0, 'renamed' => 0, 'edges_added' => 0, 'edges_removed' => 0, 'waiting' => 0, 'failed' => []];
        $created = []; $renamed = [];
        $conn->beginTransaction();
        try {
            $ciOf = [];   // kind:virtId => CI id, for the edges
            foreach (self::KINDS as $kind => $table) {
                $classId = $m[$kind . '_class_id'];
                if (!$classId) { continue; }
                foreach (self::objects($conn, $connectionId, $kind) as $o) {
                    $s = self::status($conn, $c, $m, $kind, $o);
                    if ($s['status'] === 'bound') {
                        $ciOf["$kind:{$o['id']}"] = $s['ci_id'];
                        if ($m['sync_names'] && $o['name'] !== null && $o['name'] !== '' && $s['ci_name'] !== $o['name']) {
                            $conn->prepare('UPDATE cmdb_objects SET name = ?, updated_datetime = UTC_TIMESTAMP() WHERE id = ?')->execute([mb_substr($o['name'], 0, 255), $s['ci_id']]);
                            $renamed[] = $s['ci_id'];
                        }
                        continue;
                    }
                    if ($s['status'] !== 'create') { if ($s['status'] !== 'left') { $sum['waiting']++; } continue; }
                    $conn->exec('SAVEPOINT virt_cmdb_obj');
                    try {
                        $ciId = CmdbService::createInTransaction($conn, $actor, ['name' => (string)$o['name'], 'class_id' => $classId, 'tenant_id' => $tenant]);
                        self::bind($conn, $ciId, $connectionId, $kind, (int)$o['id'], null, $source);
                        $conn->exec('RELEASE SAVEPOINT virt_cmdb_obj');
                        $ciOf["$kind:{$o['id']}"] = $ciId;
                        $created[] = $ciId;
                    } catch (ServiceError $e) {
                        $conn->exec('ROLLBACK TO SAVEPOINT virt_cmdb_obj');
                        $sum['failed'][] = $o['name'] . ': ' . $e->getMessage();
                    }
                }
            }
            [$sum['edges_added'], $sum['edges_removed']] = self::syncEdges($conn, $actor, $connectionId, $m, $ciOf);
            $sum['created'] = count($created);
            $sum['renamed'] = count($renamed);
            $text = sprintf('%d created, %d renamed, %d relationships added, %d removed, %d waiting for a decision', $sum['created'], $sum['renamed'],
                $sum['edges_added'], $sum['edges_removed'], $sum['waiting']) . ($sum['failed'] ? '; ' . count($sum['failed']) . ' could not be created' : '');
            $conn->prepare('UPDATE virt_cmdb_mappings SET last_sync_at = UTC_TIMESTAMP(), last_sync_summary = ? WHERE connection_id = ?')
                ->execute([mb_substr($text, 0, 500), $connectionId]);
            $conn->commit();
        } catch (Throwable $e) {
            if ($conn->inTransaction()) { $conn->rollBack(); }
            throw $e;
        }
        foreach ($created as $id) { CmdbService::announceCreated($conn, $id); }
        foreach ($renamed as $id) {
            try { WorkflowEngine::dispatch('cmdb.object.updated', ['object' => ['id' => $id]]); } catch (Throwable) { }
        }
        return $sum;
    }

    /**
     * The relationships the sync owns: make the wanted ones, remove the ones it made that are
     * no longer true (a VM that moved). A relationship a person drew with the same ends and
     * type is left alone - not duplicated, not adopted.
     *
     * @return array{0:int,1:int} added, removed
     */
    private static function syncEdges(PDO $conn, ActorContext $actor, int $connectionId, array $m, array $ciOf): array
    {
        $want = [];
        $add = function (?int $from, ?int $to, ?int $type) use (&$want) {
            if ($from && $to && $type && $from !== $to) { $want["$from|$to|$type"] = [$from, $to, $type]; }
        };
        $connId = (int)$connectionId;
        if ($m['member_type_id']) {
            foreach ($conn->query("SELECT id, cluster_id FROM virt_hosts WHERE connection_id = $connId AND not_seen_since IS NULL AND cluster_id IS NOT NULL") as $h) {
                $add($ciOf["host:{$h['id']}"] ?? null, $ciOf["cluster:{$h['cluster_id']}"] ?? null, $m['member_type_id']);
            }
        }
        foreach ($conn->query("SELECT id, host_id, is_clustered FROM virt_vms WHERE connection_id = $connId AND not_seen_since IS NULL AND host_id IS NOT NULL") as $v) {
            // Only a highly available role can fail over: an unknown is treated as not.
            $type = (int)$v['is_clustered'] === 1 && $v['is_clustered'] !== null ? $m['runs_on_ha_type_id'] : $m['runs_on_type_id'];
            $add($ciOf["vm:{$v['id']}"] ?? null, $ciOf["host:{$v['host_id']}"] ?? null, $type);
        }
        if ($m['role_in_type_id']) {
            foreach ($conn->query("SELECT id, cluster_id FROM virt_cluster_roles WHERE connection_id = $connId AND not_seen_since IS NULL AND cluster_id IS NOT NULL") as $r) {
                $add($ciOf["role:{$r['id']}"] ?? null, $ciOf["cluster:{$r['cluster_id']}"] ?? null, $m['role_in_type_id']);
            }
        }
        $have = [];
        foreach ($conn->query("SELECT r.id, r.from_object_id, r.to_object_id, r.relationship_type_id FROM cmdb_relationship_sources s
                JOIN cmdb_object_relationships r ON r.id = s.relationship_id WHERE s.connection_id = $connId")->fetchAll(PDO::FETCH_ASSOC) as $r) {
            $have["{$r['from_object_id']}|{$r['to_object_id']}|{$r['relationship_type_id']}"] = (int)$r['id'];
        }
        // An edge the sync made for an object that is now not seen stays: not seen is not gone.
        $keepNotSeen = [];
        foreach ($have as $key => $relId) {
            if (isset($want[$key])) { continue; }
            [$from] = explode('|', $key);
            if (!in_array((int)$from, $ciOf, true)) { $keepNotSeen[$key] = true; }
        }
        $added = 0; $removed = 0;
        $exists = $conn->prepare('SELECT 1 FROM cmdb_object_relationships WHERE from_object_id = ? AND to_object_id = ? AND relationship_type_id = ?');
        foreach ($want as $key => [$from, $to, $type]) {
            if (isset($have[$key])) { continue; }
            $exists->execute([$from, $to, $type]);
            if ($exists->fetchColumn()) { continue; }   // a person already drew it
            $rel = CmdbService::createRelationship($conn, $actor, $from, ['to_object_id' => $to, 'relationship_type_id' => $type]);
            $conn->prepare('INSERT INTO cmdb_relationship_sources (relationship_id, connection_id, created_at) VALUES (?, ?, UTC_TIMESTAMP())')->execute([$rel['id'], $connId]);
            $added++;
        }
        foreach ($have as $key => $relId) {
            if (isset($want[$key]) || isset($keepNotSeen[$key])) { continue; }
            $conn->prepare('DELETE FROM cmdb_object_relationships WHERE id = ?')->execute([$relId]);
            $conn->prepare('DELETE FROM cmdb_relationship_sources WHERE relationship_id = ?')->execute([$relId]);
            $removed++;
        }
        return [$added, $removed];
    }

    // ======================================================================
    //  Hooks from elsewhere
    // ======================================================================

    /**
     * A connection is about to be deleted (api/assets/hyperv_connections.php, inside its
     * transaction). Its CIs are kept and become ordinary CIs: every live binding ends with the
     * row kept (the CI still says it was discovered once), and the relationships the sync made
     * stay as a person's own. The mapping and the remembered decisions go - they mean nothing
     * without the connection.
     */
    public static function connectionDeleted(PDO $conn, ActorContext $ctx, int $connectionId): void
    {
        if (!self::ready($conn)) { return; }
        $st = $conn->prepare('SELECT * FROM cmdb_object_sources WHERE connection_id = ? AND ended_at IS NULL');
        $st->execute([$connectionId]);
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $b) {
            self::end($conn, $b, 'connection_deleted', $ctx->actorId > 0 ? $ctx->actorId : null, $ctx->source === 'api' ? 'api' : 'ui');
        }
        foreach (['cmdb_relationship_sources', 'virt_cmdb_decisions', 'virt_cmdb_mappings'] as $t) {
            $conn->prepare("DELETE FROM $t WHERE connection_id = ?")->execute([$connectionId]);
        }
    }

    /**
     * CIs are being deleted by a person (CmdbService::deleteObject, inside its transaction):
     * their bindings end, and their objects wait for a decision instead of being recreated
     * on the next report. Sync-owned relationships touching them go with the relationships.
     *
     * @param int[] $ciIds
     */
    public static function ciDeleted(PDO $conn, ActorContext $ctx, array $ciIds): void
    {
        if (!$ciIds || !self::ready($conn)) { return; }
        $in = implode(',', array_map('intval', $ciIds));
        foreach ($conn->query("SELECT * FROM cmdb_object_sources WHERE ended_at IS NULL AND cmdb_object_id IN ($in)")->fetchAll(PDO::FETCH_ASSOC) as $b) {
            self::end($conn, $b, 'ci_deleted', $ctx->actorId > 0 ? $ctx->actorId : null, $ctx->source === 'api' ? 'api' : 'ui');
            self::remember($conn, (int)$b['connection_id'], $b['object_kind'], (int)$b['virt_object_id'], 'ci_deleted', $ctx->actorId > 0 ? $ctx->actorId : null);
        }
        $conn->exec("DELETE s FROM cmdb_relationship_sources s JOIN cmdb_object_relationships r ON r.id = s.relationship_id
            WHERE r.from_object_id IN ($in) OR r.to_object_id IN ($in)");
    }

    /**
     * A reviewed rebinding ("same machine", VirtualEstateService::decideReview) is about to
     * remove the NEW row. The old row keeps its binding. If only the new one had a CI, the
     * binding moves to the old row; if both had one, the new one's ends (its CI is kept - a
     * person decides about it).
     */
    public static function rebound(PDO $conn, string $kind, int $oldId, int $newId): void
    {
        if (!self::ready($conn)) { return; }
        $new = self::liveBinding($conn, $kind, $newId);
        if (!$new) { return; }
        if (self::liveBinding($conn, $kind, $oldId)) {
            self::end($conn, $new, 'rebound', null, 'system');
        } else {
            $conn->prepare('UPDATE cmdb_object_sources SET virt_object_id = ? WHERE id = ?')->execute([$oldId, (int)$new['id']]);
        }
        self::forget($conn, $kind, $newId);
    }

    /** Ids of discovered objects with a live binding - the retention purge must keep these. */
    public static function boundIdsSql(string $kind, string $idColumn = 'id'): string
    {
        return "$idColumn NOT IN (SELECT virt_object_id FROM cmdb_object_sources WHERE object_kind = '" . preg_replace('/[^a-z]/', '', $kind) . "' AND ended_at IS NULL)";
    }

    // ======================================================================
    //  Reading, for the CI page and impact
    // ======================================================================

    /**
     * The CI's From discovery card: what it is bound to, read live from the estate, and every
     * earlier binding. A reader who cannot open Assets (where the estate lives) gets
     * {restricted: true} - the same rule as the asset card (GH #201).
     */
    public static function forCi(PDO $conn, ActorContext $ctx, array $access, int $ciId): array
    {
        $out = ['ready' => self::ready($conn), 'current' => null, 'history' => []];
        if (!$out['ready']) { return $out; }
        // LEFT JOIN: a binding ended by its connection being deleted is still history.
        $st = $conn->prepare('SELECT s.*, c.name AS connection_name FROM cmdb_object_sources s LEFT JOIN virt_connections c ON c.id = s.connection_id
            WHERE s.cmdb_object_id = ? ORDER BY s.id DESC');
        $st->execute([$ciId]);
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $b) {
            $entry = ['started_at' => $b['started_at'], 'start_source' => $b['start_source'], 'ended_at' => $b['ended_at'], 'end_reason' => $b['end_reason']];
            if ($b['ended_at'] === null) {
                $out['current'] = empty($access['assets_read']) ? ['restricted' => true]
                    : ['kind' => $b['object_kind'], 'connection' => $b['connection_name']] + self::describe($conn, $b['object_kind'], (int)$b['virt_object_id']);
            }
            $out['history'][] = empty($access['assets_read']) ? ['restricted' => true] + array_intersect_key($entry, ['started_at' => 1, 'ended_at' => 1])
                : $entry + ['kind' => $b['object_kind'], 'connection' => $b['connection_name']];
        }
        return $out;
    }

    /**
     * A bound CI's state for impact (cmdbMemberState): 'down' if not seen or reported down,
     * 'unknown' if the last report could not read it, 'up' if it was read and is up. Null
     * when nothing is bound, or the state is not something discovery reports.
     */
    public static function memberState(PDO $conn, int $ciId): ?string
    {
        if (!self::ready($conn)) { return null; }
        $b = self::liveBindingForCi($conn, $ciId);
        if (!$b || !in_array($b['object_kind'], ['host', 'vm', 'cluster', 'role'], true)) { return null; }
        $row = self::object($conn, $b['object_kind'], (int)$b['virt_object_id'], null);
        if (!$row) { return null; }
        if ($row['not_seen_since'] !== null) { return 'down'; }
        if ($row['not_confirmed_since'] !== null) { return 'unknown'; }
        $state = strtolower((string)($row['node_state'] ?? $row['state'] ?? ''));
        if (in_array($state, ['down', 'off', 'failed', 'offline'], true)) { return 'down'; }
        return 'up';
    }

    /**
     * The CIs a bound VM may run on - its possible owners, as CIs - or null when discovery
     * does not say (a CI drawn by hand, or a VM that is not a clustered role).
     *
     * @return ?int[]
     */
    public static function possibleOwnerCis(PDO $conn, int $vmCiId): ?array
    {
        if (!self::ready($conn)) { return null; }
        $b = self::liveBindingForCi($conn, $vmCiId);
        if (!$b || $b['object_kind'] !== 'vm') { return null; }
        $row = self::object($conn, 'vm', (int)$b['virt_object_id'], null);
        $hosts = json_decode((string)($row['possible_owners_json'] ?? ''), true) ?: [];
        if (!$hosts) { return null; }
        $in = implode(',', array_map('intval', $hosts));
        return array_map('intval', $conn->query("SELECT cmdb_object_id FROM cmdb_object_sources WHERE object_kind = 'host' AND ended_at IS NULL AND virt_object_id IN ($in)")->fetchAll(PDO::FETCH_COLUMN));
    }

    // ======================================================================
    //  Internals
    // ======================================================================

    /** What the sync would do with one object. */
    private static function status(PDO $conn, array $c, array $m, string $kind, array $o): array
    {
        $b = self::liveBinding($conn, $kind, (int)$o['id']);
        if ($b) {
            $st = $conn->prepare('SELECT name FROM cmdb_objects WHERE id = ?');
            $st->execute([(int)$b['cmdb_object_id']]);
            return ['status' => 'bound', 'ci_id' => (int)$b['cmdb_object_id'], 'ci_name' => (string)$st->fetchColumn()];
        }
        if (in_array($kind, ['vm', 'host'], true)) {
            $st = $conn->prepare("SELECT 1 FROM virt_identity_reviews WHERE status = 'open' AND object_kind = ? AND new_object_id = ?");
            $st->execute([$kind, (int)$o['id']]);
            if ($st->fetchColumn()) { return ['status' => 'waiting']; }
        }
        $st = $conn->prepare('SELECT decision FROM virt_cmdb_decisions WHERE object_kind = ? AND virt_object_id = ?');
        $st->execute([$kind, (int)$o['id']]);
        $d = $st->fetchColumn();
        if ($d === 'leave_unbound') { return ['status' => 'left']; }
        if ($d === 'ci_deleted') { return ['status' => 'ci_deleted']; }
        if ($d === 'create_new') { return ['status' => 'create']; }
        $st = $conn->prepare('SELECT o.id, o.name FROM cmdb_objects o WHERE o.class_id = ? AND COALESCE(o.tenant_id, ?) = ? AND LOWER(o.name) = LOWER(?)
              AND NOT EXISTS (SELECT 1 FROM cmdb_object_sources s WHERE s.cmdb_object_id = o.id AND s.ended_at IS NULL) ORDER BY o.id LIMIT 10');
        $st->execute([(int)$m[$kind . '_class_id'], getDefaultTenantId($conn), self::tenantId($conn, $c['tenant_id']), (string)$o['name']]);
        $matches = array_map(fn($r) => ['id' => (int)$r['id'], 'name' => $r['name']], $st->fetchAll(PDO::FETCH_ASSOC));
        return $matches ? ['status' => 'match', 'matches' => $matches] : ['status' => 'create'];
    }

    /** The seen objects of one kind on a connection (VM roles are the VMs, so not listed as roles). */
    private static function objects(PDO $conn, int $connectionId, string $kind): array
    {
        $table = self::KINDS[$kind];
        $extra = $kind === 'role' ? " AND (role_type IS NULL OR role_type NOT IN ('" . implode("','", self::NOT_ROLES) . "'))" : '';
        $st = $conn->prepare("SELECT * FROM $table WHERE connection_id = ? AND not_seen_since IS NULL$extra ORDER BY name");
        $st->execute([$connectionId]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    private static function object(PDO $conn, string $kind, int $id, ?int $connectionId): ?array
    {
        $st = $conn->prepare('SELECT * FROM ' . self::KINDS[$kind] . ' WHERE id = ?' . ($connectionId ? ' AND connection_id = ' . (int)$connectionId : ''));
        $st->execute([$id]);
        $r = $st->fetch(PDO::FETCH_ASSOC);
        if (!$r && $connectionId) { throw new ServiceError('not_found', 'not_found', 'Discovered object not found.'); }
        return $r ?: null;
    }

    /** The discovered facts the From discovery card shows - read live, never stored on the CI. */
    private static function describe(PDO $conn, string $kind, int $id): array
    {
        $r = self::object($conn, $kind, $id, null);
        if (!$r) { return ['exists' => false]; }
        $name = function (?string $table, $rowId) use ($conn) {
            if (!$rowId) { return null; }
            $st = $conn->prepare("SELECT name FROM $table WHERE id = ?");
            $st->execute([(int)$rowId]);
            return $st->fetchColumn() ?: null;
        };
        return ['exists' => true, 'name' => $r['name'], 'external_id' => $r['external_id'],
            'state' => $r['state'] ?? $r['node_state'] ?? null,
            'host' => isset($r['host_id']) ? $name('virt_hosts', $r['host_id']) : null,
            'cluster' => array_key_exists('cluster_id', $r) ? $name('virt_clusters', $r['cluster_id']) : null,
            'highly_available' => array_key_exists('is_clustered', $r) && $r['is_clustered'] !== null ? (bool)$r['is_clustered'] : null,
            'identity_kind' => $r['identity_kind'] ?? null,
            'cpu_count' => isset($r['cpu_count']) ? (int)$r['cpu_count'] : null,
            'memory_bytes' => isset($r['memory_bytes']) ? (int)$r['memory_bytes'] : null,
            'last_seen' => $r['last_seen_observed_at'], 'not_seen_since' => $r['not_seen_since'], 'not_confirmed_since' => $r['not_confirmed_since']];
    }

    private static function liveBinding(PDO $conn, string $kind, int $virtId): ?array
    {
        $st = $conn->prepare('SELECT * FROM cmdb_object_sources WHERE object_kind = ? AND virt_object_id = ? AND ended_at IS NULL LIMIT 1');
        $st->execute([$kind, $virtId]);
        return $st->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    private static function liveBindingForCi(PDO $conn, int $ciId): ?array
    {
        $st = $conn->prepare('SELECT * FROM cmdb_object_sources WHERE cmdb_object_id = ? AND ended_at IS NULL LIMIT 1');
        $st->execute([$ciId]);
        return $st->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    private static function bind(PDO $conn, int $ciId, int $connectionId, string $kind, int $virtId, ?int $by, string $source): void
    {
        $conn->prepare('INSERT INTO cmdb_object_sources (cmdb_object_id, connection_id, object_kind, virt_object_id, started_at, started_by_id, start_source)
            VALUES (?, ?, ?, ?, UTC_TIMESTAMP(), ?, ?)')->execute([$ciId, $connectionId, $kind, $virtId, $by, $source]);
    }

    private static function end(PDO $conn, array $b, string $reason, ?int $by, string $source): void
    {
        $st = $conn->prepare('SELECT name FROM cmdb_objects WHERE id = ?');
        $st->execute([(int)$b['cmdb_object_id']]);
        $conn->prepare('UPDATE cmdb_object_sources SET ended_at = UTC_TIMESTAMP(), ended_by_id = ?, end_source = ?, end_reason = ?, ci_name = COALESCE(?, ci_name)
            WHERE id = ? AND ended_at IS NULL')->execute([$by, $source, $reason, $st->fetchColumn() ?: null, (int)$b['id']]);
    }

    private static function remember(PDO $conn, int $connectionId, string $kind, int $virtId, string $decision, ?int $by): void
    {
        $conn->prepare('INSERT INTO virt_cmdb_decisions (object_kind, virt_object_id, connection_id, decision, decided_by_id, decided_at)
            VALUES (?, ?, ?, ?, ?, UTC_TIMESTAMP()) ON DUPLICATE KEY UPDATE decision = VALUES(decision), decided_by_id = VALUES(decided_by_id), decided_at = VALUES(decided_at)')
            ->execute([$kind, $virtId, $connectionId, $decision, $by]);
    }

    private static function forget(PDO $conn, string $kind, int $virtId): void
    {
        $conn->prepare('DELETE FROM virt_cmdb_decisions WHERE object_kind = ? AND virt_object_id = ?')->execute([$kind, $virtId]);
    }

    private static function loadCi(PDO $conn, int $id, bool $lock): array
    {
        $st = $conn->prepare('SELECT id, class_id, tenant_id, name FROM cmdb_objects WHERE id = ?' . ($lock ? ' FOR UPDATE' : ''));
        $st->execute([$id]);
        $r = $st->fetch(PDO::FETCH_ASSOC);
        if (!$r) { throw new ServiceError('not_found', 'not_found', 'CI not found.'); }
        return $r;
    }

    private static function exists(PDO $conn, string $table, int $id): bool
    {
        $st = $conn->prepare("SELECT 1 FROM $table WHERE id = ? AND COALESCE(is_active, 1) = 1");
        $st->execute([$id]);
        return (bool)$st->fetchColumn();
    }

    /** NULL tenant_id is the Default company's. */
    private static function tenantId(PDO $conn, $tenantId): int
    {
        return $tenantId === null ? (int)getDefaultTenantId($conn) : (int)$tenantId;
    }
}

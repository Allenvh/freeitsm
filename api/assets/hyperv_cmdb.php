<?php
/**
 * API: Assets -> Settings -> Hyper-V -> (a connection) -> CMDB (GH #199 Part B) - the mapping
 * onto your CMDB classes and relationship types, the preview, decisions about single objects,
 * and Sync now.
 *
 * GET  ?connection_id=N                  the mapping, the preview, and the classes and types to choose from
 * POST {action:'save', connection_id, cluster_class_id, ..., sync_names, enabled}
 *                                        switching it on runs the first sync at once
 * POST {action:'suggest'}                the suggested classes and types, made if missing (needs the CMDB schema capabilities)
 * POST {action:'decide', connection_id, kind, id, decision: bind|create_new|leave|reset|unbind, ci_id?}
 * POST {action:'sync', connection_id}
 *
 * Needs the Hyper-V settings capability AND write access to the CMDB, and the connection's
 * company. Every rule is in VirtualEstateCmdbService.
 *
 * @wiki Hyper-V-Discovery-Developer-Guide
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/services/virtual_estate.php';
require_once '../../includes/services/virtual_estate_cmdb.php';

header('Content-Type: application/json');
if (!isset($_SESSION['analyst_id'])) { http_response_code(401); echo json_encode(['success' => false, 'error' => 'Not authenticated']); exit; }
requireModuleAccessJson('assets');
requireCapabilityJson(Cap::ASSETS_HYPERV);
requireModuleAccessJson('cmdb');
requireModuleWriteJson('cmdb');

/** Ends the request with a JSON error. */
function hcFail(string $error, ?string $code = null): never
{
    echo json_encode(['success' => false, 'error' => $error] + ($code ? ['code' => $code] : []));
    exit;
}

try {
    $conn = connectToDatabase();
    $analyst = (int)$_SESSION['analyst_id'];
    $ctx = ActorContext::fromSession($conn);
    if (!VirtualEstateCmdbService::ready($conn)) { hcFail('Run Database Verification first (System -> Database Verification).', 'not_ready'); }
    $load = function (int $id) use ($conn, $analyst): array {
        try { $c = VirtualEstateService::connection($conn, $id); } catch (ServiceError $e) { hcFail('Connection not found.'); }
        $tenant = $c['tenant_id'] === null ? getDefaultTenantId($conn) : (int)$c['tenant_id'];
        if (!analystCanAccessTenant($conn, $analyst, $tenant)) { hcFail('Connection not found.'); }
        return $c;
    };

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET') {
        $c = $load((int)($_GET['connection_id'] ?? 0));
        $classes = $conn->query('SELECT id, name FROM cmdb_classes WHERE is_active = 1 ORDER BY display_order, name')->fetchAll(PDO::FETCH_ASSOC);
        $types = $conn->query("SELECT id, verb, impact_direction, redundancy FROM cmdb_relationship_types WHERE COALESCE(is_active, 1) = 1 ORDER BY display_order, verb")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'connection' => ['id' => (int)$c['id'], 'name' => $c['name']],
            'preview' => VirtualEstateCmdbService::preview($conn, (int)$c['id']), 'classes' => $classes, 'types' => $types,
            'can_suggest' => analystHasCapability($conn, $analyst, Cap::CMDB_CLASSES) && analystHasCapability($conn, $analyst, Cap::CMDB_RELATIONSHIP_TYPES)]);
        exit;
    }

    csrfRequireWriteMethod();
    $in = json_decode(file_get_contents('php://input'), true) ?: [];
    switch ($in['action'] ?? '') {
        case 'save':
            $c = $load((int)($in['connection_id'] ?? 0));
            $was = VirtualEstateCmdbService::mapping($conn, (int)$c['id']);
            $m = VirtualEstateCmdbService::saveMapping($conn, $ctx, (int)$c['id'], $in);
            $sync = (!$was['enabled'] && $m['enabled']) ? VirtualEstateCmdbService::sync($conn, (int)$c['id']) : null;
            echo json_encode(['success' => true, 'mapping' => $m, 'sync' => $sync]);
            exit;
        case 'suggest':
            // It may create CMDB classes and relationship types: the CMDB's schema permissions.
            requireCapabilityJson(Cap::CMDB_CLASSES, $conn);
            requireCapabilityJson(Cap::CMDB_RELATIONSHIP_TYPES, $conn);
            $conn->beginTransaction();
            $s = VirtualEstateCmdbService::suggest($conn);
            $conn->commit();
            echo json_encode(['success' => true, 'suggested' => $s]);
            exit;
        case 'decide':
            $c = $load((int)($in['connection_id'] ?? 0));
            $r = VirtualEstateCmdbService::decide($conn, $ctx, (int)$c['id'], (string)($in['kind'] ?? ''), (int)($in['id'] ?? 0),
                (string)($in['decision'] ?? ''), isset($in['ci_id']) ? (int)$in['ci_id'] : null);
            echo json_encode(['success' => true] + $r);
            exit;
        case 'sync':
            $c = $load((int)($in['connection_id'] ?? 0));
            $r = VirtualEstateCmdbService::sync($conn, (int)$c['id']);
            if ($r === null) { hcFail('Switch the mapping on first.', 'not_enabled'); }
            echo json_encode(['success' => true, 'sync' => $r]);
            exit;
    }
    hcFail('Unknown action.');
} catch (ServiceError $e) {
    hcFail($e->getMessage(), $e->errorCode);
} catch (Throwable $e) {
    if (isset($conn) && $conn->inTransaction()) { $conn->rollBack(); }
    error_log('hyperv_cmdb: ' . $e->getMessage());
    hcFail('The CMDB mapping could not be loaded or saved.');
}

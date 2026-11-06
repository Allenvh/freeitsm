<?php
/**
 * API: the asset <-> configuration item link (GH #201), for the asset page and the CI page.
 * Every rule lives in AssetCiLinkService; this only routes and decides who is asking.
 *
 * GET  ?asset_id=N                         the link seen from the asset
 * GET  ?cmdb_object_id=N                   the link seen from the CI (with the asset card)
 * GET  ?search=cis&asset_id=N&q=           CIs in the asset's company, for the picker
 * GET  ?search=assets&cmdb_object_id=N&q=  assets in the CI's company, for the picker
 * GET  ?merge_preview=1&keep=N&gone=M      the live links of two assets about to be merged
 * GET  ?create_preview=1&asset_id=N         Create linked CI: the type's class, its required properties, possible matches
 * POST {action:'create_ci', asset_id, name, properties, ignore_matches?}  the CI and its link, in one transaction
 * POST {action:'link', asset_id, cmdb_object_id, replace?, replaces?:[link ids]}
 * POST {action:'unlink', asset_id | cmdb_object_id, link_id}
 *
 * Shared by two modules, so the gate is "either module"; the service then asks for the
 * side being read, and for write access to BOTH sides before anything changes.
 *
 * @wiki Asset-CI-Link-Developer-Guide
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/services/asset_ci_links.php';

header('Content-Type: application/json');
if (!isset($_SESSION['analyst_id'])) { http_response_code(401); echo json_encode(['success' => false, 'error' => 'Not authenticated']); exit; }
requireAnyModuleAccessJson(['assets', 'cmdb'], null, 'assets');   // owner: GH #217

/** Ends the request with a JSON error. */
function ciLinkFail(string $error, int $status = 200, ?string $code = null): never
{
    http_response_code($status);
    echo json_encode(['success' => false, 'error' => $error] + ($code ? ['code' => $code] : []));
    exit;
}

try {
    $conn = connectToDatabase();
    $analyst = (int)$_SESSION['analyst_id'];
    $ctx = ActorContext::fromSession($conn);
    $access = AssetCiLinkService::accessForAnalyst($conn, $analyst);

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
        csrfRequireWriteMethod();
        $in = json_decode(file_get_contents('php://input'), true) ?: [];
        $assetId = isset($in['asset_id']) ? (int)$in['asset_id'] : null;
        $ciId = isset($in['cmdb_object_id']) ? (int)$in['cmdb_object_id'] : null;
        switch ($in['action'] ?? '') {
            case 'link':
                if (!$assetId || !$ciId) { ciLinkFail('An asset and a configuration item are required.'); }
                $res = AssetCiLinkService::link($conn, $ctx, $access, $assetId, $ciId, !empty($in['replace']),
                    is_array($in['replaces'] ?? null) ? $in['replaces'] : [], 'ui');
                echo json_encode(['success' => true] + $res);
                exit;
            case 'create_ci':
                if (!$assetId) { ciLinkFail('An asset is required.'); }
                $res = AssetCiLinkService::createLinkedCi($conn, $ctx, $access, $assetId,
                    ['name' => $in['name'] ?? '', 'properties' => is_array($in['properties'] ?? null) ? $in['properties'] : []],
                    !empty($in['ignore_matches']), 'ui');
                echo json_encode(['success' => true] + $res);
                exit;
            case 'unlink':
                if (!$assetId && !$ciId) { ciLinkFail('An asset or a configuration item is required.'); }
                $res = AssetCiLinkService::unlink($conn, $ctx, $access, $assetId ?: null, $assetId ? null : $ciId,
                    isset($in['link_id']) ? (int)$in['link_id'] : null, 'ui');
                echo json_encode(['success' => true] + $res);
                exit;
        }
        ciLinkFail('Unknown action.');
    }

    if (!empty($_GET['create_preview'])) {
        // The matches are CIs, so this needs both modules.
        if (!$access['assets_read'] || !$access['cmdb_read']) { ciLinkFail('You do not have access to this module', 403); }
        echo json_encode(['success' => true] + AssetCiLinkService::createPreview($conn, $ctx, $access, (int)($_GET['asset_id'] ?? 0)));
        exit;
    }

    if (!empty($_GET['merge_preview'])) {
        if (!$access['assets_read']) { ciLinkFail('You do not have access to this module', 403); }
        echo json_encode(['success' => true] + AssetCiLinkService::mergePreview($conn, $ctx, $access, (int)($_GET['keep'] ?? 0), (int)($_GET['gone'] ?? 0)));
        exit;
    }

    $search = (string)($_GET['search'] ?? '');
    $q = trim((string)($_GET['q'] ?? ''));
    $like = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $q) . '%';

    if ($search === 'cis') {
        // The picker on the asset: CIs in the asset's company. Needs both modules.
        if (!$access['assets_read'] || !$access['cmdb_read']) { ciLinkFail('You do not have access to this module', 403); }
        $assetId = (int)($_GET['asset_id'] ?? 0);
        if (!analystCanAccessAsset($conn, $analyst, $assetId)) { ciLinkFail('Asset not found'); }
        $t = $conn->prepare('SELECT tenant_id FROM assets WHERE id = ?');
        $t->execute([$assetId]);
        $tenant = $t->fetchColumn();
        $tenant = $tenant === null || $tenant === false ? getDefaultTenantId($conn) : (int)$tenant;
        $classSql = !empty($_GET['class_id']) ? ' AND o.class_id = ' . (int)$_GET['class_id'] : '';
        $st = $conn->prepare("SELECT o.id, o.name, c.name AS class_name, i.icon_key,
                (SELECT COUNT(*) FROM asset_ci_links l WHERE l.cmdb_object_id = o.id AND l.unlinked_at IS NULL) AS linked
            FROM cmdb_objects o JOIN cmdb_classes c ON c.id = o.class_id LEFT JOIN cmdb_icons i ON i.id = c.icon_id
            WHERE COALESCE(o.tenant_id, ?) = ? AND o.name LIKE ?$classSql ORDER BY o.name LIMIT 25");
        $st->execute([getDefaultTenantId($conn), $tenant, $like]);
        $rows = array_map(fn($r) => ['id' => (int)$r['id'], 'name' => $r['name'], 'class_name' => $r['class_name'],
            'icon_key' => $r['icon_key'], 'linked' => (int)$r['linked'] > 0], $st->fetchAll(PDO::FETCH_ASSOC));
        echo json_encode(['success' => true, 'results' => $rows]);
        exit;
    }

    if ($search === 'assets') {
        // The picker on the CI: assets in the CI's company. Needs both modules.
        if (!$access['assets_read'] || !$access['cmdb_read']) { ciLinkFail('You do not have access to this module', 403); }
        $ciId = (int)($_GET['cmdb_object_id'] ?? 0);
        if (!analystCanAccessCmdbObject($conn, $analyst, $ciId)) { ciLinkFail('Configuration item not found'); }
        $t = $conn->prepare('SELECT tenant_id FROM cmdb_objects WHERE id = ?');
        $t->execute([$ciId]);
        $tenant = $t->fetchColumn();
        $tenant = $tenant === null || $tenant === false ? getDefaultTenantId($conn) : (int)$tenant;
        $st = $conn->prepare("SELECT a.id, a.hostname, a.service_tag, a.asset_tag, ty.name AS type_name,
                (SELECT COUNT(*) FROM asset_ci_links l WHERE l.asset_id = a.id AND l.unlinked_at IS NULL) AS linked
            FROM assets a LEFT JOIN asset_types ty ON ty.id = a.asset_type_id
            WHERE COALESCE(a.tenant_id, ?) = ? AND (a.hostname LIKE ? OR a.service_tag LIKE ? OR a.asset_tag LIKE ?)
            ORDER BY a.hostname LIMIT 25");
        $st->execute([getDefaultTenantId($conn), $tenant, $like, $like, $like]);
        $rows = array_map(fn($r) => ['id' => (int)$r['id'], 'name' => $r['hostname'], 'serial' => $r['service_tag'],
            'asset_tag' => $r['asset_tag'], 'type_name' => $r['type_name'], 'linked' => (int)$r['linked'] > 0], $st->fetchAll(PDO::FETCH_ASSOC));
        echo json_encode(['success' => true, 'results' => $rows]);
        exit;
    }

    if (isset($_GET['asset_id'])) {
        if (!$access['assets_read']) { ciLinkFail('You do not have access to this module', 403); }
        // Part D: the asset card's "Open as diagram" is a Network Mapper right, as on the CI page.
        $view = AssetCiLinkService::forAsset($conn, $ctx, $access, (int)$_GET['asset_id']);
        // Part C: "Create linked CI" shows only on a type mapped to a CMDB class.
        $createClass = null;
        if ($view['current'] === null && $access['cmdb_read']) {
            $t = $conn->prepare('SELECT asset_type_id FROM assets WHERE id = ?');
            $t->execute([(int)$_GET['asset_id']]);
            $createClass = AssetCiLinkService::mappedClass($conn, ($tid = $t->fetchColumn()) ? (int)$tid : null);
        }
        echo json_encode(['success' => true, 'diagram_access' => $access['cmdb_read'] && analystCanAccessModule($conn, $analyst, 'network-mapper'),
            'create_class' => $createClass] + $view);
        exit;
    }
    if (isset($_GET['cmdb_object_id'])) {
        if (!$access['cmdb_read']) { ciLinkFail('You do not have access to this module', 403); }
        echo json_encode(['success' => true] + AssetCiLinkService::forCi($conn, $ctx, $access, (int)$_GET['cmdb_object_id']));
        exit;
    }
    ciLinkFail('An asset or a configuration item is required.');
} catch (ServiceError $e) {
    if (in_array($e->errorCode, ['ci_already_linked', 'asset_already_linked'], true) && !empty($assetId) && !empty($ciId)) {
        echo json_encode(['success' => false, 'error' => $e->getMessage(), 'code' => $e->errorCode,
            'conflicts' => AssetCiLinkService::conflictIds($conn, (int)$assetId, (int)$ciId)]);
        exit;
    }
    ciLinkFail($e->getMessage(), 200, $e->errorCode);
} catch (Throwable $e) {
    error_log('ci_link: ' . $e->getMessage());
    ciLinkFail('The link could not be loaded or saved.');
}

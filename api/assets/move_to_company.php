<?php
/**
 * API: Assets — move an asset to another company (2.6.0).
 *
 * Thin UI adapter over AssetsService::moveToCompany, which holds every rule:
 * both-ends access, the hostname / asset tag / holder refusals, and clearing a
 * location, type or status the new company cannot see. Nothing is decided
 * here. The Assets twin of api/tasks/move_to_company.php.
 *
 * Plain module access, no settings capability: filing an asset under the right
 * client is ordinary analyst work, like assigning it to somebody.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/services/assets.php';
require_once '../../includes/i18n.php';
I18n::initFromSession();

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('assets');

$input    = json_decode(file_get_contents('php://input'), true) ?: [];
$assetId  = (int)($input['asset_id'] ?? 0);
$targetId = (int)($input['tenant_id'] ?? 0);

if ($assetId <= 0 || $targetId <= 0) {
    echo json_encode(['success' => false, 'error' => 'An asset and a target company are required']);
    exit;
}

try {
    $conn = connectToDatabase();
    $res  = AssetsService::moveToCompany($conn, ActorContext::fromSession($conn), $assetId, $targetId);

    // Say what was emptied, in the analyst's language, so nobody discovers a
    // blank Location later and wonders where it went.
    $cleared = array_map(fn($k) => t('asset-management.field.' . $k), $res['cleared']);
    $message = !$res['moved']
        ? t('asset-management.move.already', ['company' => $res['to']])
        : ($cleared
            ? t('asset-management.move.done_cleared', ['company' => $res['to'], 'fields' => implode(', ', $cleared)])
            : t('asset-management.move.done', ['company' => $res['to']]));

    echo json_encode(['success' => true, 'moved' => $res['moved'], 'company_name' => $res['to'], 'message' => $message]);
} catch (ServiceError $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

<?php
/**
 * API: CMDB -> Settings -> Impact (GH #199 Part C) - what "If this failed" assumes about a
 * redundant group's other members when nothing records their state.
 *
 * GET                                     {unknown_members: 'assume' | 'unknown'}
 * POST {unknown_members: 'assume'|'unknown'}   needs the Impact capability
 *
 * Either way the analysis lists the assumption (includes/cmdb_impact.php).
 *
 * @wiki CMDB-Impact-Analysis-Developer-Guide
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/cmdb_impact.php';

header('Content-Type: application/json');
if (!isset($_SESSION['analyst_id'])) { http_response_code(401); echo json_encode(['success' => false, 'error' => 'Not authenticated']); exit; }
requireModuleAccessJson('cmdb');

try {
    $conn = connectToDatabase();
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
        csrfRequireWriteMethod();
        requireCapabilityJson(Cap::CMDB_IMPACT, $conn);
        $in = json_decode(file_get_contents('php://input'), true) ?: [];
        $v = ($in['unknown_members'] ?? '') === 'unknown' ? 'unknown' : 'assume';
        $conn->prepare('INSERT INTO system_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)')
            ->execute([CMDB_IMPACT_UNKNOWN_SETTING, $v]);
        echo json_encode(['success' => true, 'unknown_members' => $v]);
        exit;
    }
    $st = $conn->prepare('SELECT setting_value FROM system_settings WHERE setting_key = ?');
    $st->execute([CMDB_IMPACT_UNKNOWN_SETTING]);
    echo json_encode(['success' => true, 'unknown_members' => $st->fetchColumn() === 'unknown' ? 'unknown' : 'assume']);
} catch (Throwable $e) {
    error_log('impact_settings: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'The setting could not be loaded or saved.']);
}

<?php
/**
 * API: past runs, one run's rows, and the holding area.
 *
 * ?runs=1            recent live runs
 * ?run_id=N          one run's per-row outcomes
 * ?unresolved=1      rows still needing attention, across every run
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/services/asset_import.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('assets');

try {
    $conn = connectToDatabase();
    if (!AssetImportService::schemaReady($conn)) {
        echo json_encode(['success' => true, 'schema_ready' => false, 'runs' => [], 'unresolved' => []]);
        exit;
    }

    if (!empty($_GET['run_id'])) {
        $runId = (int)$_GET['run_id'];
        echo json_encode([
            'success' => true, 'schema_ready' => true,
            'run'     => AssetImportService::loadRun($conn, $runId),
            'entries' => AssetImportService::runEntries($conn, $runId, $_GET['action'] ?? null, 500),
        ]);
        exit;
    }

    $out = ['success' => true, 'schema_ready' => true];

    if (!empty($_GET['unresolved'])) {
        $out['unresolved'] = AssetImportService::unresolved($conn);
    }

    if (!empty($_GET['runs'])) {
        // Live runs only. A preview is a rehearsal, not history — listing them
        // would bury the runs that actually changed something.
        $rows = $conn->query(
            "SELECT r.*, a.full_name AS analyst_name
               FROM asset_import_runs r
          LEFT JOIN analysts a ON a.id = r.triggered_by_analyst_id
              WHERE r.mode = 'live'
           ORDER BY r.id DESC LIMIT 25"
        )->fetchAll(PDO::FETCH_ASSOC);
        $out['runs'] = $rows;
        // Always present, zero included: "nothing needs attention" and "we did
        // not look" must never render the same way.
        $out['unresolved_count'] = (int)$conn->query(
            "SELECT COUNT(*) FROM asset_import_run_entries e
               JOIN asset_import_runs r ON r.id = e.run_id
              WHERE r.mode = 'live' AND e.resolved_datetime IS NULL
                AND e.action IN ('error','conflict')"
        )->fetchColumn();
    }

    echo json_encode($out);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

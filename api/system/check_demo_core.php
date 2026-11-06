<?php
/**
 * API Endpoint: Which demo data modules have been imported.
 * Returns a row count per module so the UI can reflect their state.
 *
 * This used to ask "does the table have any rows at all?" for most modules, so
 * an installation with real tickets or real assets was told its demo data was
 * already imported. It now counts only rows marked is_demo = 1 (#1297), which
 * is the same mark the importer uses to decide what it may delete, and it
 * covers every module rather than the five that happened to be listed here.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/admin_api_guard.php'; // System admins only (issue #34)
require_once '../../includes/functions.php';
require_once '../../includes/demo_data.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['exists' => false]);
    exit;
}

try {
    $conn = connectToDatabase();

    $counts  = [];
    $modules = [];
    foreach (DEMO_MODULES as $module) {
        $counts[$module]  = demoRowCount($conn, $module);
        $modules[$module] = $counts[$module] > 0;
    }

    echo json_encode([
        'exists'  => $modules['core'] ?? false,   // kept: the UI gates on core
        'modules' => $modules,
        'counts'  => $counts,
        // Demo data imported before the rows carried a mark. It cannot be
        // recognised, so a re-import would sit alongside it rather than replace
        // it. The page warns rather than pretending otherwise.
        'untagged' => demoHasUntaggedImport($conn),
    ]);
} catch (Exception $e) {
    echo json_encode(['exists' => false]);
}

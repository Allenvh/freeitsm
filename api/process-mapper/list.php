<?php
/**
 * List all processes, with what the sidebar's hover preview shows (#202):
 * who created each one and how many steps and lanes it has.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('process-mapper');

try {
    $conn = connectToDatabase();
    $stmt = $conn->query("SELECT p.id, p.title, p.description, p.created_by, a.full_name AS created_by_name,
                                 p.created_datetime, p.updated_datetime
                          FROM processes p
                          LEFT JOIN analysts a ON a.id = p.created_by
                          ORDER BY p.updated_datetime DESC");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // One grouped count per table rather than a subquery per row. Lanes sit in
    // their own try, as get.php does - process_lanes may not exist yet on an
    // install that has not run DB Verify since lanes shipped.
    $countsFor = function (string $table) use ($conn): array {
        try {
            return $conn->query("SELECT process_id, COUNT(*) FROM `$table` GROUP BY process_id")
                        ->fetchAll(PDO::FETCH_KEY_PAIR);
        } catch (Exception $e) {
            return [];
        }
    };
    $stepCounts = $countsFor('process_steps');
    $laneCounts = $countsFor('process_lanes');
    foreach ($rows as &$r) {
        $r['step_count'] = (int)($stepCounts[$r['id']] ?? 0);
        $r['lane_count'] = (int)($laneCounts[$r['id']] ?? 0);
    }
    unset($r);

    echo json_encode(['success' => true, 'data' => $rows]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

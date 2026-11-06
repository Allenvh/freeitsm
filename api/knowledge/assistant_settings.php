<?php
/**
 * API: Knowledge — the assistant's tunables.
 *
 * GET  → current values (defaults filled in where nothing is stored)
 * POST → save
 *
 * These are settings rather than constants because "how thin is too thin" is a
 * genuine judgement that differs by desk: a small internal IT team wants the bar
 * low, because any question asked twice is worth writing down. An MSP with a
 * public portal wants it high, because a thin article is a support call.
 *
 * ⚠️ Every value is clamped server-side. A cluster threshold of 0 would put every
 * closed ticket into one enormous group and a lookback of 30000 would read the
 * entire history on a button press — neither is a useful thing to allow, and
 * neither should depend on the number input's min/max holding.
 */

session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/knowledge/writeup_ai.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('knowledge');

// Same capability that guards the rest of the Knowledge AI panel.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCapabilityJson(Cap::KNOWLEDGE_AI);
}

/** key => [min, max, isFloat] */
const GAP_SETTING_BOUNDS = [
    'knowledge_gap_min_cluster'       => [2,    50,   false],
    'knowledge_gap_lookback_days'     => [7,    730,  false],
    'knowledge_gap_article_threshold' => [0.30, 0.95, true],
    'knowledge_gap_cluster_threshold' => [0.50, 0.99, true],
    'knowledge_gap_min_richness'      => [0,    100,  false],
];

try {
    $conn = connectToDatabase();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => true, 'settings' => writeupSettings($conn)]);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true) ?: [];

    $upsert = $conn->prepare(
        "INSERT INTO system_settings (setting_key, setting_value) VALUES (?, ?)
         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
    );

    $saved = [];
    foreach (GAP_SETTING_BOUNDS as $key => $bounds) {
        if (!array_key_exists($key, $input) || $input[$key] === '' || $input[$key] === null) {
            continue;
        }
        [$min, $max, $isFloat] = $bounds;
        $val = $isFloat ? (float)$input[$key] : (int)$input[$key];
        $val = max($min, min($max, $val));
        $upsert->execute([$key, (string)$val]);
        $saved[$key] = $val;
    }

    echo json_encode(['success' => true, 'saved' => $saved, 'message' => 'Saved']);

} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

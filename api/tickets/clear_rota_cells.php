<?php
/**
 * API Endpoint: clear the shifts out of many rota cells at once.
 *
 * POST { targets: [ { analyst_id, rota_date } ] }  ->  { success, removed }
 *
 * The other half of paste_rota_cells.php: a dragged selection, a whole column
 * (one day, every analyst) or a whole row (one analyst, every day), emptied
 * in one transaction rather than N delete_rota_entry.php calls.
 *
 * 🔴 THIS ONE DELETES, so the scoping matters more here than anywhere else in
 * the rota. Both scopes are derived HERE, never taken from the request, for
 * the reason paste_rota_week.php spells out - a delete scoped by whatever the
 * caller sent is a delete scoped by whatever the caller sent:
 *
 *   - ONLY ACTIVE ANALYSTS. get_rota.php lists analysts WHERE is_active = 1,
 *     so a deactivated analyst's entries are already invisible on the page and
 *     must not be destroyed by a gesture aimed at the grid.
 *   - ONLY THE DAYS ON SCREEN. With rota_include_weekends off the grid is
 *     Monday to Friday; Saturday and Sunday entries exist and are not drawn.
 *
 * It also deletes ONLY the cells it was handed, one (analyst, date) pair at a
 * time. There is no range delete in here on purpose.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('tickets');
requireModuleOnJson(connectToDatabase(), 'rota');   // GH #217: not while the duty rota is switched off

$input = json_decode(file_get_contents('php://input'), true);
$targets = is_array($input['targets'] ?? null) ? $input['targets'] : [];

if (!$targets) {
    echo json_encode(['success' => false, 'error' => 'No cells to clear']);
    exit;
}
if (count($targets) > 500) {
    echo json_encode(['success' => false, 'error' => 'Too many cells in one request']);
    exit;
}

try {
    $conn = connectToDatabase();

    $setting = $conn->query("SELECT setting_value FROM system_settings WHERE setting_key = 'rota_include_weekends'")->fetchColumn();
    $includeWeekends = $setting !== false ? (int)$setting : 0;

    $activeIds = array_flip(array_map('intval',
        $conn->query("SELECT id FROM analysts WHERE is_active = 1")->fetchAll(PDO::FETCH_COLUMN)));

    $valid = [];
    $skipped = 0;
    foreach ($targets as $tgt) {
        $analystId = (int)($tgt['analyst_id'] ?? 0);
        $date      = trim((string)($tgt['rota_date'] ?? ''));

        if (!isset($activeIds[$analystId]) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            $skipped++;
            continue;
        }
        $d = DateTime::createFromFormat('Y-m-d', $date);
        if (!$d || $d->format('Y-m-d') !== $date || (!$includeWeekends && (int)$d->format('N') > 5)) {
            $skipped++;
            continue;
        }
        $valid[$analystId . '|' . $date] = [$analystId, $date];
    }

    if (!$valid) {
        echo json_encode(['success' => false, 'error' => 'No valid cells to clear']);
        exit;
    }

    $conn->beginTransaction();
    $del = $conn->prepare("DELETE FROM ticket_rota_entries WHERE analyst_id = ? AND rota_date = ?");
    $removed = 0;
    foreach ($valid as $cell) {
        $del->execute($cell);
        $removed += $del->rowCount();
    }
    $conn->commit();

    echo json_encode(['success' => true, 'removed' => $removed, 'skipped' => $skipped]);

} catch (Exception $e) {
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

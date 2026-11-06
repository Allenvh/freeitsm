<?php
/**
 * API Endpoint: paste one copied shift into many cells at once.
 *
 * POST { shift_id, location_id, is_on_call,
 *        mode: 'all' | 'empty',
 *        targets: [ { analyst_id, rota_date } ] }
 *   -> { success, written, skipped_filled, skipped_invalid }
 *
 * Backs three gestures on the rota, which are the same operation with a
 * different list of cells: a dragged selection, a whole column (one day, every
 * analyst) and a whole row (one analyst, every day).
 *
 * ONE CALL, NOT THIRTY-FIVE, for the same reason paste_rota_week.php is one
 * call: firing save_rota_entry.php per cell would leave half a column pasted
 * behind the first failure, and the person would have no idea which half.
 *
 * 🔴 "EMPTY ONLY" IS DECIDED HERE, NOT BY THE CALLER. The grid the person was
 * looking at may be seconds old, and a colleague filling one of those cells in
 * the meantime is exactly the case the option exists to protect. The occupied
 * cells are re-read inside the transaction, so "don't overwrite anything"
 * means nothing that is there NOW, not nothing that was there when the page
 * last loaded.
 *
 * Every target is checked against the same two scopes paste_rota_week.php
 * derives server-side, and for the same reason — the client already knows
 * them, but a write scoped by whatever the caller sent is scoped by whatever
 * the caller sent:
 *
 *   - ONLY ACTIVE ANALYSTS. get_rota.php lists analysts WHERE is_active = 1.
 *   - ONLY THE DAYS ON SCREEN. With rota_include_weekends off, Saturday and
 *     Sunday entries exist and are simply not drawn; an overwrite must not
 *     reach them.
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
if (!$input) {
    echo json_encode(['success' => false, 'error' => 'Invalid request data']);
    exit;
}

$shiftId  = (int)($input['shift_id'] ?? 0);
$mode     = ($input['mode'] ?? 'all') === 'empty' ? 'empty' : 'all';
$targets  = is_array($input['targets'] ?? null) ? $input['targets'] : [];
$isOnCall = !empty($input['is_on_call']) ? 1 : 0;
$locationId = isset($input['location_id']) && $input['location_id'] !== null && $input['location_id'] !== ''
    ? (int)$input['location_id'] : null;

if (!$shiftId || !$targets) {
    echo json_encode(['success' => false, 'error' => 'A shift and at least one cell are required']);
    exit;
}

// A visible week is seven days times however many analysts are on the team.
// Anything past this is not a gesture anybody made on the grid.
if (count($targets) > 500) {
    echo json_encode(['success' => false, 'error' => 'Too many cells in one paste']);
    exit;
}

try {
    $conn = connectToDatabase();

    if (!$conn->query("SELECT id FROM ticket_rota_shifts WHERE id = " . $shiftId)->fetchColumn()) {
        // The shift was retired between the copy and the paste.
        echo json_encode(['success' => false, 'error' => 'That shift no longer exists']);
        exit;
    }

    if ($locationId !== null
        && !$conn->query("SELECT id FROM rota_locations WHERE id = " . $locationId)->fetchColumn()) {
        $locationId = null;   // the location was retired since the copy
    }
    if ($locationId === null) {
        // Matches save_rota_entry.php, which the single-cell paste goes
        // through. Two paste gestures leaving different rows behind would be
        // a worse surprise than inheriting its default-location behaviour.
        $locationId = (int)$conn->query("SELECT id FROM rota_locations WHERE is_default = 1 LIMIT 1")->fetchColumn() ?: null;
    }

    $setting = $conn->query("SELECT setting_value FROM system_settings WHERE setting_key = 'rota_include_weekends'")->fetchColumn();
    $includeWeekends = $setting !== false ? (int)$setting : 0;

    $activeIds = array_flip(array_map('intval',
        $conn->query("SELECT id FROM analysts WHERE is_active = 1")->fetchAll(PDO::FETCH_COLUMN)));

    // Validate first, write second. Nothing is written until every cell in the
    // list has been judged, so the counts reported back are the whole truth.
    $valid = [];
    $skippedInvalid = 0;
    foreach ($targets as $tgt) {
        $analystId = (int)($tgt['analyst_id'] ?? 0);
        $date      = trim((string)($tgt['rota_date'] ?? ''));

        if (!isset($activeIds[$analystId]) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            $skippedInvalid++;
            continue;
        }
        $d = DateTime::createFromFormat('Y-m-d', $date);
        if (!$d || $d->format('Y-m-d') !== $date) {
            $skippedInvalid++;
            continue;
        }
        if (!$includeWeekends && (int)$d->format('N') > 5) {
            $skippedInvalid++;   // a day the grid does not draw
            continue;
        }
        $valid[$analystId . '|' . $date] = ['analyst_id' => $analystId, 'rota_date' => $date];
    }

    if (!$valid) {
        echo json_encode(['success' => false, 'error' => 'No valid cells to paste into']);
        exit;
    }

    $conn->beginTransaction();

    // Which of those cells are occupied right now. Read inside the transaction
    // so "empty only" means empty at the moment of writing.
    $occupied = [];
    if ($mode === 'empty') {
        $analystList = array_values(array_unique(array_column($valid, 'analyst_id')));
        $dateList    = array_values(array_unique(array_column($valid, 'rota_date')));
        $sql = "SELECT analyst_id, rota_date FROM ticket_rota_entries
                 WHERE analyst_id IN (" . implode(',', array_map('intval', $analystList)) . ")
                   AND rota_date IN (" . implode(',', array_fill(0, count($dateList), '?')) . ")";
        $occStmt = $conn->prepare($sql);
        $occStmt->execute($dateList);
        foreach ($occStmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $occupied[$row['analyst_id'] . '|' . $row['rota_date']] = true;
        }
    }

    // The unique key on (analyst_id, rota_date) means pasting onto an empty
    // cell and pasting over a full one are the same statement.
    $ins = $conn->prepare(
        "INSERT INTO ticket_rota_entries (analyst_id, rota_date, shift_id, location_id, is_on_call, created_datetime, updated_datetime)
         VALUES (?, ?, ?, ?, ?, UTC_TIMESTAMP(), UTC_TIMESTAMP())
         ON DUPLICATE KEY UPDATE shift_id = VALUES(shift_id), location_id = VALUES(location_id),
             is_on_call = VALUES(is_on_call), updated_datetime = UTC_TIMESTAMP()"
    );

    $written = 0;
    $skippedFilled = 0;
    foreach ($valid as $key => $cell) {
        if ($mode === 'empty' && isset($occupied[$key])) {
            $skippedFilled++;
            continue;
        }
        $ins->execute([$cell['analyst_id'], $cell['rota_date'], $shiftId, $locationId, $isOnCall]);
        $written++;
    }

    $conn->commit();

    echo json_encode([
        'success'         => true,
        'written'         => $written,
        'skipped_filled'  => $skippedFilled,
        'skipped_invalid' => $skippedInvalid,
    ]);

} catch (Exception $e) {
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

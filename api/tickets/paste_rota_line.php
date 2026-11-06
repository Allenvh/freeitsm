<?php
/**
 * API Endpoint: paste a copied column or row over another one.
 *
 * POST { kind: 'col' | 'row',
 *        week_start: 'YYYY-MM-DD',
 *        date: 'YYYY-MM-DD',          // kind = col: the day being pasted ONTO
 *        analyst_id: 12,              // kind = row: the analyst being pasted ONTO
 *        entries: [ { analyst_id | day_offset, shift_id, location_id, is_on_call } ] }
 *   -> { success, removed, written, skipped }
 *
 * Distinct from paste_rota_cells.php, which stamps ONE shift into many cells.
 * A line carries a different shift per cell, so it is the week paste's
 * problem rather than the cell paste's, and it is solved the same way:
 *
 * 🔴 A LINE PASTE REPLACES THE LINE, exactly as a week paste replaces the
 * week. Copying Monday and pasting it onto Tuesday has to leave Tuesday
 * looking like Monday, which means clearing Tuesday cells Monday did not
 * have. Merging would produce a day matching neither, which is worse than
 * either.
 *
 * 🔴 AND "REPLACE" MUST NOT REACH PAST THE GRID. Both scopes are derived
 * HERE, never taken from the request:
 *
 *   - ONLY ACTIVE ANALYSTS. get_rota.php lists analysts WHERE is_active = 1,
 *     so a deactivated analyst's entries are already invisible on the page.
 *   - ONLY THE DAYS ON SCREEN. With rota_include_weekends off the grid is
 *     Monday to Friday, and Saturday and Sunday entries exist and are simply
 *     not drawn. A row paste covers the drawn days and no more.
 *
 * What travels in `entries` is deliberately asymmetric, and mirrors what the
 * paste is FOR:
 *   - a COLUMN is one day for every analyst, so each entry is keyed by
 *     ANALYST and lands on the same person on a different day;
 *   - a ROW is one analyst across the week, so each entry is keyed by DAY
 *     OFFSET and lands on the same day for a different person.
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
$kind  = ($input['kind'] ?? '') === 'row' ? 'row' : (($input['kind'] ?? '') === 'col' ? 'col' : '');

if (!$kind || empty($input['week_start'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid request data']);
    exit;
}

$entries = is_array($input['entries'] ?? null) ? $input['entries'] : [];

try {
    $conn = connectToDatabase();

    // Normalise to the Monday, the same way get_rota.php does.
    $dt = new DateTime($input['week_start']);
    $dt->modify('-' . ((int)$dt->format('N') - 1) . ' days');
    $weekStart = $dt->format('Y-m-d');

    $setting = $conn->query("SELECT setting_value FROM system_settings WHERE setting_key = 'rota_include_weekends'")->fetchColumn();
    $numDays = ($setting !== false ? (int)$setting : 0) ? 7 : 5;

    $lastDay = new DateTime($weekStart);
    $lastDay->modify('+' . ($numDays - 1) . ' days');
    $rangeEnd = $lastDay->format('Y-m-d');

    $activeIds = array_map('intval',
        $conn->query("SELECT id FROM analysts WHERE is_active = 1")->fetchAll(PDO::FETCH_COLUMN));
    if (!$activeIds) {
        echo json_encode(['success' => false, 'error' => 'No active analysts']);
        exit;
    }
    $activeSet = array_flip($activeIds);
    $inList = implode(',', $activeIds);

    // A shift deleted between the copy and the paste would otherwise fail on
    // the foreign key halfway through, rolling the lot back with nothing said.
    $validShifts = array_flip(array_map('intval',
        $conn->query("SELECT id FROM ticket_rota_shifts")->fetchAll(PDO::FETCH_COLUMN)));
    $validLocs = array_flip(array_map('intval',
        $conn->query("SELECT id FROM rota_locations")->fetchAll(PDO::FETCH_COLUMN)));

    // Work out which cells the line covers, and check the target is one the
    // grid actually draws. Both are derived here, not trusted.
    if ($kind === 'col') {
        $date = trim((string)($input['date'] ?? ''));
        $d = DateTime::createFromFormat('Y-m-d', $date);
        if (!$d || $d->format('Y-m-d') !== $date || $date < $weekStart || $date > $rangeEnd) {
            echo json_encode(['success' => false, 'error' => 'That day is not on this grid']);
            exit;
        }
        $delSql = "DELETE FROM ticket_rota_entries WHERE rota_date = ? AND analyst_id IN ($inList)";
        $delArgs = [$date];
    } else {
        $analystId = (int)($input['analyst_id'] ?? 0);
        if (!isset($activeSet[$analystId])) {
            echo json_encode(['success' => false, 'error' => 'That analyst is not on this grid']);
            exit;
        }
        $delSql = "DELETE FROM ticket_rota_entries WHERE analyst_id = ? AND rota_date BETWEEN ? AND ?";
        $delArgs = [$analystId, $weekStart, $rangeEnd];
    }

    $conn->beginTransaction();

    $del = $conn->prepare($delSql);
    $del->execute($delArgs);
    $removed = $del->rowCount();

    $ins = $conn->prepare(
        "INSERT INTO ticket_rota_entries (analyst_id, rota_date, shift_id, location_id, is_on_call, created_datetime, updated_datetime)
         VALUES (?, ?, ?, ?, ?, UTC_TIMESTAMP(), UTC_TIMESTAMP())
         ON DUPLICATE KEY UPDATE shift_id = VALUES(shift_id), location_id = VALUES(location_id),
             is_on_call = VALUES(is_on_call), updated_datetime = UTC_TIMESTAMP()"
    );

    $written = 0;
    $skipped = 0;
    foreach ($entries as $e) {
        $shiftId = (int)($e['shift_id'] ?? 0);

        if ($kind === 'col') {
            // Same person, different day.
            $targetAnalyst = (int)($e['analyst_id'] ?? 0);
            $targetDate = $date;
        } else {
            // Same day, different person.
            $targetAnalyst = $analystId;
            $offset = (int)($e['day_offset'] ?? -1);
            if ($offset < 0 || $offset >= $numDays) { $skipped++; continue; }
            $od = new DateTime($weekStart);
            $od->modify('+' . $offset . ' days');
            $targetDate = $od->format('Y-m-d');
        }

        // Anything rejected is counted and reported: a paste that quietly
        // loses somebody's shift is the worst thing this can do.
        if (!isset($activeSet[$targetAnalyst]) || !isset($validShifts[$shiftId])) {
            $skipped++;
            continue;
        }

        $locationId = isset($e['location_id']) && $e['location_id'] !== null ? (int)$e['location_id'] : null;
        if ($locationId !== null && !isset($validLocs[$locationId])) {
            $locationId = null;   // the location was retired since the copy
        }

        $ins->execute([$targetAnalyst, $targetDate, $shiftId, $locationId, !empty($e['is_on_call']) ? 1 : 0]);
        $written++;
    }

    $conn->commit();

    echo json_encode([
        'success' => true,
        'removed' => $removed,
        'written' => $written,
        'skipped' => $skipped,
    ]);

} catch (Exception $e) {
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

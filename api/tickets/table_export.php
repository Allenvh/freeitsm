<?php
/**
 * API Endpoint: the ticket table as CSV (PR #213, by @Dr4inder)
 *
 * GET: the same parameters as table.php, plus columns (comma-separated column
 * keys - the ones visible in the table). Returns EVERY matching ticket, not one
 * page, as tickets.csv.
 *
 * 🔒 Who may use it is a setting - Tickets -> Settings -> General -> Who can export
 * the ticket list - Administrators only by default. It is checked here on every
 * request, not only by hiding the button, so a direct request is refused too.
 * It is a bulk export of ticket data, which is why it is a setting at all.
 *
 * The rows come from the same ticketListSql() as the table, so the CSV is what
 * the table shows across all its pages, in the same order.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/ticket_list.php';
require_once '../../includes/i18n.php';
I18n::initFromSession();   // the header row uses the column names on screen

if (!isset($_SESSION['analyst_id'])) {
    http_response_code(401);
    exit;
}
requireModuleAccessJson('tickets');

/** Rows read per query. Bounded, so a very long export never holds every row in memory at once. */
const TICKET_EXPORT_BATCH = 500;

$conn = null;
try {
    $conn = connectToDatabase();
    $analystId = (int)$_SESSION['analyst_id'];
    if (!ticketListCanExport($conn, $analystId)) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Ticket list export is not permitted']);
        exit;
    }

    $query = ticketListQuery($_GET);

    // Only columns the table offers, each named once.
    $allowed = ticketListExportColumns();
    if (!isset($_GET['columns']) || !is_string($_GET['columns'])) {
        throw new InvalidArgumentException('Choose export columns');
    }
    $columns = explode(',', $_GET['columns']);
    if (count($columns) > count($allowed) || array_diff($columns, array_keys($allowed))) {
        throw new InvalidArgumentException('Invalid export columns');
    }

    [$sql, $args] = ticketListSql($conn, $analystId, $query);
    $sql = "SELECT * FROM ($sql) queue" . ticketListOrder($query);

    // Written to a memory buffer (spilling to the system temp directory, never the
    // web root, past 2 MB) and only sent once complete - so a failure half way
    // still returns an error, not half a file that looks like a whole one.
    $out = fopen('php://temp/maxmemory:2097152', 'w+');
    if ($out === false || fwrite($out, "\xEF\xBB\xBF") !== 3) {   // BOM, so Excel reads UTF-8
        throw new RuntimeException('Export buffer unavailable');
    }
    $header = array_map(fn($key) => t('tickets.queue.columns.' . $key), $columns);
    if (fputcsv($out, $header, ',', '"', '') === false) throw new RuntimeException('Export buffer unavailable');

    // One transaction, so every batch reads the same snapshot: a ticket closed
    // between two batches cannot move and be exported twice, or not at all.
    $conn->beginTransaction();
    $offset = 0;
    do {
        $stmt = $conn->prepare($sql . ' LIMIT ' . TICKET_EXPORT_BATCH . ' OFFSET ' . $offset);
        $stmt->execute($args);
        $read = 0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cells = [];
            foreach ($columns as $key) {
                $value = $row[$allowed[$key]];
                // TRAP: created and snoozed-until are UTC timestamps, so they get a Z.
                // The work start and end are the wall-clock times the analyst typed,
                // with no zone at all - a Z on those would invent one.
                if ($value && in_array($key, ['created_datetime', 'work_start_datetime', 'work_end_datetime', 'snoozed_until'], true)) {
                    $value = str_replace(' ', 'T', $value);
                    if (in_array($key, ['created_datetime', 'snoozed_until'], true)) $value .= 'Z';
                }
                $cells[] = ticketListCsvCell($value);
            }
            if (fputcsv($out, $cells, ',', '"', '') === false) throw new RuntimeException('Export buffer unavailable');
            $read++;
        }
        $offset += $read;
    } while ($read === TICKET_EXPORT_BATCH && !connection_aborted());
    $conn->commit();

    if (!rewind($out)) throw new RuntimeException('Export buffer unavailable');
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="tickets.csv"');
    header('Cache-Control: no-store');
    fpassthru($out);
    fclose($out);
} catch (Throwable $e) {
    if ($conn && $conn->inTransaction()) $conn->rollBack();
    if (!headers_sent()) {
        $badRequest = $e instanceof InvalidArgumentException || $e instanceof JsonException;
        http_response_code($badRequest ? 400 : 503);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Export unavailable']);
    }
}

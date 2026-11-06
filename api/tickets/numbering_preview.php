<?php
/**
 * API: what the next few ticket numbers would look like under a proposed
 * configuration, and whether that configuration is usable.
 *
 * 🔑 Writes NOTHING. It never touches a counter, so an administrator can try
 * six formats and still have the next real ticket be the number they expect.
 * The preview in the settings screen calls this on every keystroke, and the
 * Save handler calls it once more before saving — so what is refused here is
 * refused everywhere.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/ticket_numbering.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('tickets');
requireCapabilityJson(Cap::TICKETS_NUMBERING);

try {
    $data = json_decode(file_get_contents('php://input'), true) ?: [];

    $cfg = [
        'ticket_number_style'  => $data['style']  ?? 'sequential',
        'ticket_number_format' => (string)($data['format'] ?? 'TICKET-{######}'),
        'ticket_number_start'  => (string)(int)($data['start'] ?? 1),
        'ticket_number_scope'  => $data['scope'] ?? 'global',
    ];

    $problems = ($cfg['ticket_number_style'] === 'random')
        ? []                                            // the fixed historical shape
        : TicketNumbering::validateFormat($cfg['ticket_number_format'], $cfg['ticket_number_scope']);

    // 🔴 THE FORMAT CAN BE PERFECTLY VALID AND STILL PRODUCE DUPLICATES.
    // {COMPANY} is only as distinguishing as the codes behind it, and a code
    // FreeITSM derived from a name is a convenience rather than a guarantee:
    // "Acme Ltd" and "Acme Group" both come out as ACM. So when somebody asks
    // to count per company, the actual companies are checked — naming the ones
    // that clash, because "there is a clash" is useless without "between whom".
    if ($cfg['ticket_number_scope'] === 'per_company'
        && $cfg['ticket_number_style'] !== 'random'
        && str_contains($cfg['ticket_number_format'], '{COMPANY}')) {
        $conn = connectToDatabase();
        foreach (TicketNumbering::codeClashes($conn) as $code => $names) {
            $problems[] = $code === ''
                ? 'These companies have no usable code: ' . implode(', ', $names)
                  . '. Give each one a ticket code under System → Companies.'
                : 'These companies would share the code ' . $code . ': ' . implode(', ', $names)
                  . '. Give each one its own ticket code under System → Companies.';
        }
    }

    echo json_encode([
        'success'  => true,
        'problems' => $problems,
        // Still previewed when there are problems — seeing the wrong output is
        // often what explains the message.
        'examples' => $problems ? [] : TicketNumbering::preview($cfg, 3),
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

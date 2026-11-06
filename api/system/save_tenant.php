<?php
/**
 * API: Create or update a company (tenant).
 * POST JSON { id?, name, is_active }
 *
 * "Company" is the user-facing word for a tenant; the underlying table/code
 * stays `tenants`. is_default is out of scope here and never edited.
 *
 * GUARD: the default company (is_default = 1) can never be set inactive.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/admin_api_guard.php'; // System admins only (issue #34)
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/ticket_numbering.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!is_array($data)) {
    echo json_encode(['success' => false, 'error' => 'Invalid request data']);
    exit;
}

// --- Validate fields ---
$name = trim($data['name'] ?? '');
if ($name === '') {
    echo json_encode(['success' => false, 'error' => 'Name is required']);
    exit;
}
$isActive = !empty($data['is_active']) ? 1 : 0;
$id       = isset($data['id']) ? (int)$data['id'] : 0;

// The short code that stands in for this company in a ticket number
// ({COMPANY}). Blank is a real answer meaning "derive one from the name", so
// it is stored as NULL rather than an empty string — the two are asked apart
// by TicketNumbering::codeFor().
$ticketCode = TicketNumbering::cleanCode((string)($data['ticket_code'] ?? ''));


try {
    $conn = connectToDatabase();

    // ⚠️ Two companies sharing a ticket code means two companies producing the
    // same ticket numbers under per-company counting. Refused here rather than
    // discovered later, because by then the numbers are in people's inboxes.
    //
    // Only an EXPLICIT code is refused. A derived one that happens to clash is
    // reported on the numbering screen instead — blocking a company from being
    // saved over a setting the install may not even use would be an obstruction
    // rather than a guard.
    if ($ticketCode !== '') {
        $stmt = $conn->prepare(
            "SELECT name FROM tenants WHERE UPPER(ticket_code) = ? AND id <> ? LIMIT 1"
        );
        $stmt->execute([$ticketCode, $id]);
        $clash = $stmt->fetchColumn();
        if ($clash !== false) {
            echo json_encode(['success' => false,
                'error' => 'The ticket code ' . $ticketCode . ' is already used by ' . $clash . '.']);
            exit;
        }
    }

    if ($id > 0) {
        // --- Update existing company ---
        $existing = getTenantById($conn, $id);
        if (!$existing) {
            echo json_encode(['success' => false, 'error' => 'Company not found']);
            exit;
        }
        // Never let the default company be deactivated.
        if ($existing['is_default'] && !$isActive) {
            echo json_encode(['success' => false, 'error' => 'The default company cannot be set inactive']);
            exit;
        }

        $stmt = $conn->prepare("UPDATE tenants SET name = ?, is_active = ?, ticket_code = ? WHERE id = ?");
        $stmt->execute([$name, $isActive, ($ticketCode !== '' ? $ticketCode : null), $id]);
        echo json_encode(['success' => true, 'id' => $id]);

    } else {
        // --- Create new company (always non-default) ---
        $stmt = $conn->prepare(
            "INSERT INTO tenants (name, is_default, is_active, ticket_code, created_datetime)
             VALUES (?, 0, ?, ?, UTC_TIMESTAMP())"
        );
        $stmt->execute([$name, $isActive, ($ticketCode !== '' ? $ticketCode : null)]);
        echo json_encode(['success' => true, 'id' => (int)$conn->lastInsertId()]);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

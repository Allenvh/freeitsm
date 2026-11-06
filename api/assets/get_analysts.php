<?php
/**
 * API Endpoint: the analysts who can be given an asset.
 *
 * Assets could only be held by a REQUESTER until 2.5.0, and analysts are not
 * requesters - on a real install most of the desk has no `users` row at all, so
 * the existing people search could not find them. This is the analyst half of
 * the same picker.
 *
 * 🔑 NOT tenant-scoped, deliberately. Analysts are install-wide and every other
 * analyst picker in the product (ticket assignment, knowledge ownership, change
 * approval) shows the whole desk, so scoping this one would be the odd one out
 * without hiding anything the caller cannot already see elsewhere.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

requireModuleAccessJson('assets');

try {
    $conn = connectToDatabase();

    $search = trim((string)($_GET['search'] ?? ''));

    // is_active = 1: somebody who has left the desk should not turn up as a
    // candidate to hand a laptop to. Rows already pointing at them still
    // resolve, because the display joins do not filter on it - past custody
    // stays readable.
    $sql = "SELECT id, full_name AS display_name, email FROM analysts WHERE is_active = 1";
    $params = [];
    if ($search !== '') {
        $sql .= " AND (full_name LIKE ? OR email LIKE ?)";
        $params[] = '%' . $search . '%';
        $params[] = '%' . $search . '%';
    }
    $sql .= " ORDER BY full_name LIMIT 50";

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    echo json_encode(['success' => true, 'analysts' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

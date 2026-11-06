<?php
/**
 * API: create or update a supplier contact.
 *
 * Fires the supplier_contact 'created' or 'updated' workflow event.
 *
 * @input  id           the contact to update - omit to create one
 * @input  supplier_id  the supplier the contact works for (optional)
 * @input  first_name   required
 * @input  surname      required
 * @input  email, mobile, job_title, direct_dial, switchboard  optional contact details
 * @input  is_active    1 or 0 (default 1)
 * @output {success, id}
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
requireModuleAccessJson('contracts');

try {
    $data = json_decode(file_get_contents('php://input'), true);

    $id = $data['id'] ?? null;
    $supplier_id = $data['supplier_id'] ?? null;
    $first_name = trim($data['first_name'] ?? '');
    $surname = trim($data['surname'] ?? '');
    $email = trim($data['email'] ?? '');
    $mobile = trim($data['mobile'] ?? '');
    $job_title = trim($data['job_title'] ?? '') ?: null;
    $direct_dial = trim($data['direct_dial'] ?? '') ?: null;
    $switchboard = trim($data['switchboard'] ?? '') ?: null;
    $is_active = $data['is_active'] ?? 1;

    if (empty($first_name) || empty($surname)) {
        throw new Exception('First name and surname are required');
    }

    $conn = connectToDatabase();

    if ($id) {
        $sql = "UPDATE contacts SET supplier_id = ?, first_name = ?, surname = ?, email = ?, mobile = ?, job_title = ?, direct_dial = ?, switchboard = ?, is_active = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$supplier_id ?: null, $first_name, $surname, $email ?: null, $mobile ?: null, $job_title, $direct_dial, $switchboard, $is_active, $id]);
    } else {
        $sql = "INSERT INTO contacts (supplier_id, first_name, surname, email, mobile, job_title, direct_dial, switchboard, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$supplier_id ?: null, $first_name, $surname, $email ?: null, $mobile ?: null, $job_title, $direct_dial, $switchboard, $is_active]);
        $id = $conn->lastInsertId();
        $isNew = true;
    }

    wf_emit('supplier_contact', empty($isNew) ? 'updated' : 'created', (int)$id, trim("$first_name $surname"));
    echo json_encode(['success' => true, 'id' => $id]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

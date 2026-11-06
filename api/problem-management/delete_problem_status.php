<?php
/**
 * API: delete a problem status (Problems -> Settings).
 *
 * Refused while any problem still uses it - set it inactive instead. Fires the
 * problem_status 'deleted' workflow event.
 *
 * @input  id  the status (JSON body)
 * @output {success}
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();
header('Content-Type: application/json');
if (!isset($_SESSION['analyst_id'])) { echo json_encode(['success' => false, 'error' => 'Not authenticated']); exit; }
requireModuleAccessJson('problems');
requireCapabilityJson(Cap::PROBLEMS_STATUSES);   // settings tab — see docs/design/rbac.md
try {
    $d = json_decode(file_get_contents('php://input'), true);
    $id = (int) ($d['id'] ?? 0);
    if ($id <= 0) throw new Exception('Status ID is required');
    $conn = connectToDatabase();
    $inUse = $conn->prepare("SELECT COUNT(*) FROM problems WHERE status_id = ?");
    $inUse->execute([$id]);
    if ((int) $inUse->fetchColumn() > 0) throw new Exception('This status is in use by one or more problems. Reassign them first, or set it inactive.');
    $name = $conn->query("SELECT name FROM problem_statuses WHERE id = " . (int)$id)->fetchColumn() ?: null;
    $conn->prepare("DELETE FROM problem_statuses WHERE id = ?")->execute([$id]);
    wf_emit('problem_status', 'deleted', $id, $name);
    echo json_encode(['success' => true]);
} catch (Exception $e) { echo json_encode(['success' => false, 'error' => $e->getMessage()]); }

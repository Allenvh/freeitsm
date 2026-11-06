<?php
/**
 * API: create or update a problem priority (Problems -> Settings).
 *
 * Making one the default clears the default flag on every other priority. Fires the
 * problem_priority 'created' or 'updated' workflow event.
 *
 * @input  id             the priority to update - omit to create one
 * @input  name           required
 * @input  colour         optional
 * @input  is_default     1 to make this the default
 * @input  display_order  sort position
 * @input  is_active      1 or 0 (default 1)
 * @output {success, id}
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
requireCapabilityJson(Cap::PROBLEMS_PRIORITIES);   // settings tab — see docs/design/rbac.md
try {
    $d = json_decode(file_get_contents('php://input'), true);
    $id = (int) ($d['id'] ?? 0);
    $name = trim((string) ($d['name'] ?? ''));
    if ($name === '') throw new Exception('Name is required');
    $colour = trim((string) ($d['colour'] ?? '')) ?: null;
    $isDefault = !empty($d['is_default']) ? 1 : 0;
    $order = (int) ($d['display_order'] ?? 0);
    $isActive = isset($d['is_active']) ? (!empty($d['is_active']) ? 1 : 0) : 1;
    $conn = connectToDatabase();
    if ($isDefault) $conn->exec("UPDATE problem_priorities SET is_default = 0");
    if ($id > 0) {
        $conn->prepare("UPDATE problem_priorities SET name=?, colour=?, is_default=?, display_order=?, is_active=? WHERE id=?")
             ->execute([$name, $colour, $isDefault, $order, $isActive, $id]);
        wf_emit('problem_priority', 'updated', $id, $name);
        echo json_encode(['success' => true, 'id' => $id]);
    } else {
        $conn->prepare("INSERT INTO problem_priorities (name, colour, is_default, display_order, is_active) VALUES (?, ?, ?, ?, ?)")
             ->execute([$name, $colour, $isDefault, $order, $isActive]);
        $newId = (int) $conn->lastInsertId();
        wf_emit('problem_priority', 'created', $newId, $name);
        echo json_encode(['success' => true, 'id' => $newId]);
    }
} catch (Exception $e) { echo json_encode(['success' => false, 'error' => $e->getMessage()]); }

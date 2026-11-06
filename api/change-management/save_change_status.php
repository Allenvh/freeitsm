<?php
/**
 * API Endpoint: Save change status (create or update)
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/changes/approvals.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('changes');
requireCapabilityJson(Cap::CHANGES_STATUSES);   // Change management settings tab — see docs/design/rbac.md

try {
    $data = json_decode(file_get_contents('php://input'), true);

    $id            = $data['id'] ?? null;
    $name          = trim($data['name'] ?? '');
    $colour        = trim($data['colour'] ?? '');
    $is_closed     = !empty($data['is_closed']) ? 1 : 0;
    $is_default    = !empty($data['is_default']) ? 1 : 0;
    $display_order = (int)($data['display_order'] ?? 0);
    $is_active     = !empty($data['is_active']) ? 1 : 0;
    // The stage is what the approval rules read (includes/changes/approvals.php).
    // Empty = "not classified", allowed so a custom status can be saved first and
    // classified later - Settings marks it, and Enforce mode will refuse to start.
    $stage = (string)($data['stage'] ?? '');
    if ($stage !== '' && !changeStageIsValid($stage)) {
        throw new Exception('Unknown stage');
    }
    $stage = $stage === '' ? null : $stage;
    // A finished stage IS closed; every other stage is open. Only an unclassified
    // status keeps the Closed tick it was given.
    if ($stage !== null) {
        $is_closed = in_array($stage, CHANGE_STAGES_CLOSED, true) ? 1 : 0;
    }

    if ($name === '') {
        throw new Exception('Name is required');
    }
    if ($colour !== '' && !preg_match('/^#[0-9a-fA-F]{6}$/', $colour)) {
        throw new Exception('Colour must be a #rrggbb hex code');
    }

    $conn = connectToDatabase();
    $conn->beginTransaction();

    if ($is_default) {
        $clearSql = "UPDATE change_statuses SET is_default = 0";
        if ($id) $clearSql .= " WHERE id <> " . (int)$id;
        $conn->exec($clearSql);
    }

    if ($id) {
        $sql = "UPDATE change_statuses
                   SET name = ?, stage = ?, colour = ?, is_closed = ?, is_default = ?, display_order = ?, is_active = ?
                 WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $stage, $colour ?: null, $is_closed, $is_default, $display_order, $is_active, $id]);
    } else {
        $sql = "INSERT INTO change_statuses (name, stage, colour, is_closed, is_default, display_order, is_active)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $stage, $colour ?: null, $is_closed, $is_default, $display_order, $is_active]);
    }

    // The code moves changes INTO draft, awaiting and approved (a vote, a rejection,
    // a resubmission), so each must keep an active status to move them to. Checked
    // after the write, inside the transaction, so a rename that keeps the stage passes.
    $missing = changeStagesWithoutActiveStatus($conn);
    if ($missing) {
        throw new Exception(changeStagesMissingMessage($missing));
    }

    $hasDefault = (int) $conn->query("SELECT COUNT(*) FROM change_statuses WHERE is_default = 1")->fetchColumn();
    if ($hasDefault === 0) {
        $conn->exec("UPDATE change_statuses SET is_default = 1 ORDER BY display_order, id LIMIT 1");
    }

    $conn->commit();
    wf_emit('change_status', $id ? 'updated' : 'created', $id ? (int)$id : (int)$conn->lastInsertId(), $name);
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    if (isset($conn) && $conn->inTransaction()) $conn->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>

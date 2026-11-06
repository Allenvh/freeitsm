<?php
/**
 * API: delete a custom webhook message format. Body: { id }
 *
 * Refuses when:
 *   - it's a built-in (locked), or
 *   - a workflow still uses it — the format key is stored inside each
 *     workflow's action args, so deleting one out from under a live workflow
 *     would break it at the next fire, with an error nobody could trace back
 *     to this screen. Same in-use guard as every other settings list.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();
header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('workflow');
requireCapabilityJson(Cap::WORKFLOW_FORMATS);   // settings tab — see docs/design/rbac.md

$in = json_decode(file_get_contents('php://input'), true);
$id = isset($in['id']) ? (int)$in['id'] : 0;
if (!$id) {
    echo json_encode(['success' => false, 'error' => 'Missing id']);
    exit;
}

try {
    $conn = connectToDatabase();

    $stmt = $conn->prepare("SELECT format_key, label, is_builtin FROM webhook_message_formats WHERE id = ?");
    $stmt->execute([$id]);
    $fmt = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$fmt) {
        echo json_encode(['success' => false, 'error' => 'That message format no longer exists.']);
        exit;
    }
    if ((int)$fmt['is_builtin'] === 1) {
        echo json_encode(['success' => false, 'error' => 'Built-in formats can\'t be deleted.']);
        exit;
    }

    // In use? The key lives inside the workflow's actions JSON.
    $needle = '"preset":"' . $fmt['format_key'] . '"';
    $users = $conn->prepare(
        "SELECT name FROM workflows
          WHERE REPLACE(actions, ' ', '') LIKE CONCAT('%', ?, '%')
          ORDER BY name LIMIT 5"
    );
    $users->execute([$needle]);
    $names = $users->fetchAll(PDO::FETCH_COLUMN);
    if ($names) {
        echo json_encode([
            'success' => false,
            'error'   => 'This format is still used by ' . count($names) . ' workflow(s): '
                       . implode(', ', $names) . '. Point them at another format first — '
                       . 'deleting it now would break them the next time they fire.',
        ]);
        exit;
    }

    $conn->prepare("DELETE FROM webhook_message_formats WHERE id = ? AND is_builtin = 0")->execute([$id]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

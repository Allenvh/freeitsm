<?php
/**
 * API: record that a view was opened, and optionally make it the default.
 *
 * POST { id }                       stamp last used
 * POST { table_key, default_id }    set (or clear, with null) the personal default
 *
 * Two small writes rather than two endpoints, because the browser does them
 * together: applying a view stamps it, and ticking "use this by default" is the
 * same gesture one step later.
 *
 * ⚠️ The default is stored against the READER in user_preferences, not on the
 * view. Two people can have different defaults pointing at the same shared view,
 * and one person changing theirs must not change anybody else's.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/table_views.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

$in = json_decode(file_get_contents('php://input'), true);
if (!is_array($in)) {
    echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
    exit;
}

try {
    $conn = connectToDatabase();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $analystId = (int)$_SESSION['analyst_id'];

    if (!empty($in['id'])) {
        tableViewTouch($conn, $analystId, (int)$in['id']);
    }

    if (array_key_exists('default_id', $in)) {
        $tableKey = (string)($in['table_key'] ?? '');
        if (!in_array($tableKey, TABLE_VIEW_KEYS, true)) {
            throw new Exception('Unknown table');
        }
        $key = 'dtview_default_' . $tableKey;

        if ($in['default_id'] === null || $in['default_id'] === '') {
            $conn->prepare("DELETE FROM user_preferences WHERE analyst_id = ? AND preference_key = ?")
                 ->execute([$analystId, $key]);
        } else {
            // Reachable? Setting a default you cannot see would give you a table
            // that fails to load every morning with no way to tell why.
            $viewId = (int)$in['default_id'];
            tableViewLoad($conn, $analystId, $viewId);

            $conn->prepare(
                "INSERT INTO user_preferences (analyst_id, preference_key, preference_value, updated_datetime)
                      VALUES (?, ?, ?, UTC_TIMESTAMP())
                 ON DUPLICATE KEY UPDATE preference_value = VALUES(preference_value),
                                         updated_datetime = UTC_TIMESTAMP()"
            )->execute([$analystId, $key, (string)$viewId]);
        }
    }

    echo json_encode(['success' => true]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

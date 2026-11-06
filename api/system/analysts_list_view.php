<?php
/**
 * API: how System -> Analysts lays out its list (GH #208).
 *
 * Group by, then by, layout, sort, page size and status - plus which tree groups
 * were left open and which tab was showing. Two copies:
 *   - each analyst's own, in user_preferences (ANALYSTS_VIEW_PREF)
 *   - the install default a new viewer starts from, in system_settings
 *     (ANALYSTS_VIEW_DEFAULT), set by "Make this the default for everyone"
 *
 * Actions: `get` (both copies), `save_mine`, `reset_mine` (back to the default),
 * `save_default`. Only the layout is stored: the search box and the filters
 * start empty every visit, except Status, which is how people hide leavers.
 *
 * Every value is checked against a fixed list, so a hand-made request cannot
 * store something the page would then try to render.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/admin_api_guard.php';   // administrators only

header('Content-Type: application/json');

const ANALYSTS_VIEW_PREF    = 'system_analysts_list_view';
const ANALYSTS_VIEW_DEFAULT = 'analysts_list_default_view';

/**
 * Keep only the settings the page understands, each from its fixed list.
 *
 * @param mixed $in  the decoded view object from the request or the database
 * @return array  a complete view; anything missing or unknown is today's flat list
 */
function analystsViewNormalise($in): array {
    $in = is_array($in) ? $in : [];
    $groups = ['', 'company', 'team', 'department', 'job_title', 'status', 'admin', 'signin'];
    $pick = function (string $key, array $allowed, $fallback) use ($in) {
        $v = $in[$key] ?? $fallback;
        return in_array($v, $allowed, true) ? $v : $fallback;
    };
    $view = [
        'group1'    => $pick('group1', $groups, ''),
        'group2'    => $pick('group2', $groups, ''),
        'layout'    => $pick('layout', ['tree', 'tabs', 'jump'], 'tree'),
        'sort'      => $pick('sort', ['full_name', 'username', 'last_login'], 'full_name'),
        'page_size' => $pick('page_size', [0, 25, 50, 100], 0),
        'status'    => $pick('status', ['all', 'active', 'inactive'], 'all'),
    ];
    // A second level needs a first, and cannot repeat it.
    if ($view['group1'] === '' || $view['group2'] === $view['group1']) $view['group2'] = '';

    // Open tree groups and the showing tab are paths the page built - plain strings.
    $view['open'] = null;   // null = never touched, so the page opens the first group
    if (isset($in['open']) && is_array($in['open'])) {
        $view['open'] = [];
        foreach (array_slice($in['open'], 0, 500) as $p) {
            if (is_string($p) && strlen($p) <= 200) $view['open'][] = $p;
        }
    }
    $view['tab'] = (isset($in['tab']) && is_string($in['tab']) && strlen($in['tab']) <= 200) ? $in['tab'] : '';
    return $view;
}

/**
 * Read one stored view, or null when there is none.
 *
 * @param string $sql  a query returning the JSON in its first column
 * @param array $params  the query's parameters
 */
function analystsViewRead(PDO $conn, string $sql, array $params): ?array {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $raw = $stmt->fetchColumn();
    if ($raw === false || $raw === null || $raw === '') return null;
    $decoded = json_decode($raw, true);
    return is_array($decoded) ? analystsViewNormalise($decoded) : null;
}

try {
    $conn      = connectToDatabase();
    $in        = json_decode(file_get_contents('php://input'), true) ?: [];
    $action    = (string)($in['action'] ?? $_GET['action'] ?? 'get');
    $analystId = (int)$_SESSION['analyst_id'];

    if ($action === 'get') {
        $default = analystsViewRead($conn, "SELECT setting_value FROM system_settings WHERE setting_key = ?", [ANALYSTS_VIEW_DEFAULT]);
        $mine = analystsViewRead($conn, "SELECT preference_value FROM user_preferences WHERE analyst_id = ? AND preference_key = ?", [$analystId, ANALYSTS_VIEW_PREF]);
        echo json_encode([
            'success' => true,
            'default' => $default ?? analystsViewNormalise([]),
            'mine'    => $mine,
        ]);
        exit;
    }

    // Everything below changes data: never on a GET (csrf.php).
    csrfRequireWriteMethod();

    if ($action === 'save_mine') {
        $view = analystsViewNormalise($in['view'] ?? null);
        $conn->prepare(
            "INSERT INTO user_preferences (analyst_id, preference_key, preference_value, updated_datetime)
             VALUES (?, ?, ?, UTC_TIMESTAMP())
             ON DUPLICATE KEY UPDATE preference_value = VALUES(preference_value), updated_datetime = UTC_TIMESTAMP()"
        )->execute([$analystId, ANALYSTS_VIEW_PREF, json_encode($view)]);
        echo json_encode(['success' => true, 'view' => $view]);
        exit;
    }

    if ($action === 'reset_mine') {
        $conn->prepare("DELETE FROM user_preferences WHERE analyst_id = ? AND preference_key = ?")
             ->execute([$analystId, ANALYSTS_VIEW_PREF]);
        $default = analystsViewRead($conn, "SELECT setting_value FROM system_settings WHERE setting_key = ?", [ANALYSTS_VIEW_DEFAULT]);
        echo json_encode(['success' => true, 'view' => $default ?? analystsViewNormalise([])]);
        exit;
    }

    if ($action === 'save_default') {
        // The default is a layout, not somebody's place in it: no open groups, no tab.
        $view = analystsViewNormalise($in['view'] ?? null);
        $view['open'] = null;
        $view['tab'] = '';
        $conn->prepare(
            "INSERT INTO system_settings (setting_key, setting_value)
             VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
        )->execute([ANALYSTS_VIEW_DEFAULT, json_encode($view)]);
        echo json_encode(['success' => true, 'view' => $view]);
        exit;
    }

    echo json_encode(['success' => false, 'error' => 'Unknown action']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

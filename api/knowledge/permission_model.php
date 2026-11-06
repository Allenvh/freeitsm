<?php
/**
 * API Endpoint: the install-wide folder permission model.
 * Actions: get, preview, set
 *
 * ─────────────────────────────────────────────────────────────────────────────
 * TWO DEFENSIBLE PHILOSOPHIES, NOT A SAFETY DIAL
 *
 *   containers  You must be able to read EVERY folder above a document. A locked
 *               cabinet is locked. (Default.)
 *   filing      Permissions live on documents; folders only organise. The
 *               nearest rules win and the ones above them do not apply.
 *
 * Deliberately NOT called strict/loose: that framing reads as secure/insecure,
 * so nobody would ever choose the second on its merits — and it IS a merit
 * choice. An MSP whose folders are filing wants the second; anyone with
 * confidential matters wants the first.
 *
 * ⚠️ INSTALL-WIDE, and it has to be.
 *   - NOT per folder: mixed models in one tree make "can Bob read this?" depend
 *     on which model each ancestor happens to be in, which nobody can answer in
 *     a support call.
 *   - NOT per company: articles with tenant_id IS NULL are SHARED with every
 *     company, so a per-company posture would make one document strict for one
 *     client and loose for another.
 *
 * ⚠️ THIS IS THE HIGHEST-PRIVILEGE ACTION IN THE MODULE. It changes who can read
 * live documents with no per-document change to point at, so 'preview' exists to
 * say exactly how many documents move BEFORE anything is written, and the change
 * itself is audited.
 * ─────────────────────────────────────────────────────────────────────────────
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/knowledge/visibility.php';
require_once '../../includes/knowledge/audit.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('knowledge');
requireCapabilityJson(Cap::KNOWLEDGE_MANAGE);

$analystId = (int)$_SESSION['analyst_id'];
$method    = $_SERVER['REQUEST_METHOD'];

try {
    $conn  = connectToDatabase();
    $input = $method === 'GET' ? [] : (json_decode(file_get_contents('php://input'), true) ?: []);
    $action = $method === 'GET' ? ($_GET['action'] ?? 'get') : ($input['action'] ?? '');

    switch ($action) {
        case 'get':     handleGet($conn); break;
        case 'preview': handlePreview($conn, $_GET['model'] ?? ''); break;
        // Never on a GET: ?action=set from a link saved the empty input's default,
        // "containers", over the whole install's model (csrf.php).
        case 'set':     csrfRequireWriteMethod(); handleSet($conn, $analystId, $input); break;
        default:
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

/** Returns the install-wide folder permission model in use: 'containers' or 'filing'. */
function handleGet(PDO $conn): void
{
    echo json_encode(['success' => true, 'model' => knowledgeFolderPermissionModel($conn)]);
}

/**
 * How many documents would change hands, and for whom.
 *
 * ⚠️ COUNTED, NOT ESTIMATED, and counted PER PERSON — because "47 documents
 * change" is not a fact anybody can act on. The honest question is "who gains or
 * loses what", so this asks the real resolver, under both models, on behalf of
 * every active analyst, and reports the difference.
 *
 * Bounded deliberately: the sample stops at 25 analysts and says so, because an
 * install with 400 analysts and 10,000 articles would otherwise turn a settings
 * page into a four-million-row calculation. A capped answer that admits its cap
 * is useful; an uncapped one that times out is not, and a silent cap would be
 * worse than either.
 *
 * @param string $target  the model to compare against, from ?model= - 'filing', anything else meaning 'containers'
 */
function handlePreview(PDO $conn, string $target): void
{
    $target = ($target === 'filing') ? 'filing' : 'containers';
    $current = knowledgeFolderPermissionModel($conn);

    if ($target === $current) {
        echo json_encode(['success' => true, 'unchanged' => true, 'gain' => 0, 'lose' => 0]);
        return;
    }

    // Nothing is restricted anywhere => the two models cannot differ, and the
    // fast path in the resolver means neither costs anything to evaluate.
    if (!knowledgeAclHasAnyRows($conn)) {
        echo json_encode(['success' => true, 'unchanged' => false, 'gain' => 0, 'lose' => 0, 'sampled' => 0, 'capped' => false]);
        return;
    }

    $CAP = 25;
    $analysts = $conn->query(
        "SELECT id FROM analysts WHERE is_active = 1 ORDER BY id LIMIT " . ($CAP + 1)
    )->fetchAll(PDO::FETCH_COLUMN);
    $capped = count($analysts) > $CAP;
    $analysts = array_slice($analysts, 0, $CAP);

    $readable = function (string $model) use ($conn, $analysts): array {
        knowledgeAclResetCaches();
        $conn->prepare("INSERT INTO system_settings (setting_key, setting_value) VALUES ('knowledge_folder_permission_model', ?)
                        ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)")->execute([$model]);
        knowledgeAclResetCaches();

        $seen = [];
        foreach ($analysts as $aid) {
            $viewer = KnowledgeViewer::forAnalyst($conn, (int)$aid);
            // The administrator floor would make every analyst who holds it look
            // identical under both models, hiding the real difference. Ask
            // without it: this is a question about the MODEL, not about who can
            // override it.
            [$sql, $params] = knowledgeVisibilitySql($conn, $viewer, 'a', ['lifecycle' => 'live', 'no_admin_floor' => true]);
            $st = $conn->prepare("SELECT a.id FROM knowledge_articles a WHERE 1=1" . $sql);
            $st->execute($params);
            foreach ($st->fetchAll(PDO::FETCH_COLUMN) as $artId) {
                $seen[$aid . ':' . $artId] = true;
            }
        }
        return $seen;
    };

    // ⚠️ Both are evaluated by TEMPORARILY writing the setting, because the
    // resolver reads it rather than taking it as an argument — and the setting
    // is restored before returning whatever happens. A preview that leaves the
    // install in the model it was previewing would be catastrophic, so the
    // restore is in a finally.
    try {
        $before = $readable($current);
        $after  = $readable($target);
    } finally {
        $conn->prepare("INSERT INTO system_settings (setting_key, setting_value) VALUES ('knowledge_folder_permission_model', ?)
                        ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)")->execute([$current]);
        knowledgeAclResetCaches();
    }

    $gain = count(array_diff_key($after, $before));
    $lose = count(array_diff_key($before, $after));

    echo json_encode([
        'success'   => true,
        'unchanged' => false,
        'gain'      => $gain,
        'lose'      => $lose,
        'sampled'   => count($analysts),
        'capped'    => $capped,
    ]);
}

/**
 * Saves the install-wide folder permission model ('filing', anything else meaning
 * 'containers') and writes an audit row naming the old and new model.
 *
 * @param array $in  the decoded JSON request; model is 'filing', anything else meaning 'containers'
 * @writes system_settings(knowledge_folder_permission_model)
 */
function handleSet(PDO $conn, int $analystId, array $in): void
{
    $model = ($in['model'] ?? '') === 'filing' ? 'filing' : 'containers';
    $was   = knowledgeFolderPermissionModel($conn);

    $conn->prepare("INSERT INTO system_settings (setting_key, setting_value) VALUES ('knowledge_folder_permission_model', ?)
                    ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)")->execute([$model]);
    knowledgeAclResetCaches();

    // The highest-privilege change in the module leaves a row. It alters who can
    // read live documents with no per-document change to point at, so without
    // this there would be nothing at all to find afterwards.
    knowledgeAuditLog($conn, 'folder', 0, 'permissions', $analystId, ['permission_model_from' => $was, 'to' => $model]);

    echo json_encode(['success' => true, 'model' => $model]);
}

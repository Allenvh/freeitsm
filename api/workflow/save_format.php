<?php
/**
 * API: create or update a custom webhook message format.
 *
 * Body: { id?, key, label, body_template, url_pattern?, markdown_hint?, is_active? }
 *
 * Built-ins (is_builtin = 1) are LOCKED — same add-only model as the freemail
 * domain list. Letting someone edit Slack's body template in place would break
 * every Slack webhook on the install with no obvious cause; if they want a
 * variant, they copy it to a new key.
 *
 * The body template is validated hard, because a broken one wouldn't surface
 * until a real webhook fired.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once __DIR__ . '/../../workflow/includes/engine.php';

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
if (!is_array($in)) {
    echo json_encode(['success' => false, 'error' => 'Bad payload']);
    exit;
}

$id       = isset($in['id']) ? (int)$in['id'] : 0;
$key      = strtolower(trim((string)($in['key'] ?? '')));
$label    = trim((string)($in['label'] ?? ''));
$template = trim((string)($in['body_template'] ?? ''));
$urlPat   = trim((string)($in['url_pattern'] ?? ''));
$hint     = trim((string)($in['markdown_hint'] ?? ''));
$isActive = array_key_exists('is_active', $in) ? (!empty($in['is_active']) ? 1 : 0) : 1;

$fail = function (string $msg) {
    echo json_encode(['success' => false, 'error' => $msg]);
    exit;
};

// ---- Validate ------------------------------------------------------------
if ($key === '')   $fail('A key is required (lowercase letters, numbers, hyphens and underscores).');
if (!preg_match('/^[a-z0-9_-]+$/', $key)) {
    $fail('The key may only contain lowercase letters, numbers, hyphens and underscores — it is stored inside each workflow.');
}
if (in_array($key, WorkflowEngine::RESERVED_FORMAT_KEYS, true)) {
    $fail('"' . $key . '" is reserved — Custom and Full record are built into the engine and are not message formats.');
}
if ($label === '')    $fail('A name is required — it is what appears in the Format dropdown.');
if ($template === '') $fail('A JSON body template is required.');

// It must be valid JSON — otherwise the failure would only surface when a real
// webhook fired, as an unexplained delivery error.
$decoded = json_decode($template, true);
if ($decoded === null && strtolower($template) !== 'null') {
    $fail('The body template is not valid JSON: ' . json_last_error_msg());
}
// And it must actually use {{message}}, or the Message box in the editor would
// be a field that silently does nothing.
if (!str_contains($template, '{{message}}')) {
    $fail('The body template must include {{message}} somewhere — that is the slot your workflow\'s message is placed into.');
}
// A bad regex would throw at match time in the editor's URL check.
if ($urlPat !== '' && @preg_match('#' . $urlPat . '#i', '') === false) {
    $fail('The URL pattern is not a valid regular expression.');
}

try {
    $conn = connectToDatabase();

    // Never touch a built-in.
    if ($id) {
        $row = $conn->prepare("SELECT format_key, is_builtin FROM webhook_message_formats WHERE id = ?");
        $row->execute([$id]);
        $existing = $row->fetch(PDO::FETCH_ASSOC);
        if (!$existing) $fail('That message format no longer exists.');
        if ((int)$existing['is_builtin'] === 1) {
            $fail('Built-in formats can\'t be edited — editing Slack in place would break every Slack webhook on this install. Use Copy to make your own version.');
        }
    }

    // The key is embedded in every workflow that uses the format, so a clash
    // would silently re-point existing workflows at a different shape.
    $dupe = $conn->prepare("SELECT id FROM webhook_message_formats WHERE format_key = ? AND id <> ?");
    $dupe->execute([$key, $id]);
    if ($dupe->fetch()) $fail('The key "' . $key . '" is already in use by another format.');

    if ($id) {
        $conn->prepare(
            "UPDATE webhook_message_formats
                SET format_key = ?, label = ?, body_template = ?, url_pattern = ?, markdown_hint = ?, is_active = ?
              WHERE id = ? AND is_builtin = 0"
        )->execute([$key, $label, $template, $urlPat ?: null, $hint ?: null, $isActive, $id]);
    } else {
        $conn->prepare(
            "INSERT INTO webhook_message_formats
             (format_key, label, body_template, url_pattern, markdown_hint, is_builtin, is_active, display_order)
             VALUES (?, ?, ?, ?, ?, 0, ?, 100)"
        )->execute([$key, $label, $template, $urlPat ?: null, $hint ?: null, $isActive]);
        $id = (int)$conn->lastInsertId();
    }

    echo json_encode(['success' => true, 'id' => $id]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

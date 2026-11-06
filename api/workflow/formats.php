<?php
/**
 * API: list the webhook message formats (built-in + admin-added).
 *
 * Read-only. Used by the settings tab AND by the editor, which needs each
 * format's url_pattern (to warn when the webhook URL doesn't look like it
 * belongs to the chosen platform) and markdown_hint (so Discord's **bold** vs
 * Slack's *bold* is stated where you're typing, not in a help page).
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once __DIR__ . '/../../workflow/includes/engine.php';
header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('workflow');

try {
    $conn = connectToDatabase();
    $rows = $conn->query(
        "SELECT id, format_key, label, body_template, url_pattern, markdown_hint,
                is_builtin, is_active, display_order
           FROM webhook_message_formats
          ORDER BY display_order, label"
    )->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'formats' => array_map(fn($r) => [
            'id'            => (int)$r['id'],
            'key'           => $r['format_key'],
            'label'         => $r['label'],
            'body_template' => $r['body_template'],
            'url_pattern'   => $r['url_pattern'],
            'markdown_hint' => $r['markdown_hint'],
            'is_builtin'    => (int)$r['is_builtin'],
            'is_active'     => (int)$r['is_active'],
        ], $rows),
        // The two structural modes, so the UI can explain why they're not listed
        // as editable rows.
        'reserved' => WorkflowEngine::RESERVED_FORMAT_KEYS,
    ]);
} catch (Exception $e) {
    // Table not created yet → fall back to the engine's built-ins so the editor
    // still works before Database Verify has been run.
    $out = [];
    foreach (WorkflowEngine::BUILTIN_WEBHOOK_FORMATS as $k => $f) {
        $out[] = [
            'id' => 0, 'key' => $k, 'label' => $f['label'],
            'body_template' => $f['body_template'], 'url_pattern' => $f['url_pattern'],
            'markdown_hint' => $f['markdown_hint'], 'is_builtin' => 1, 'is_active' => 1,
        ];
    }
    echo json_encode([
        'success'  => true,
        'formats'  => $out,
        'reserved' => WorkflowEngine::RESERVED_FORMAT_KEYS,
        'notice'   => 'Showing the built-in formats — run Database Verification to enable custom ones.',
    ]);
}

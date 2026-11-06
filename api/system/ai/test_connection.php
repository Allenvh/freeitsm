<?php
/**
 * API: Test the saved AI config for a namespace with a tiny live call.
 * POST JSON { ns }
 * Returns: { success, provider, model, duration_ms, reply } or { success:false, error }
 */
session_start(['read_and_close' => true]);
require_once '../../../config.php';
require_once '../../../includes/functions.php';
require_once '../../../includes/rbac.php';
require_once '../../../includes/settings_keys.php';
require_once '../../../includes/ai_settings.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true) ?: [];
$ns = isset($data['ns']) ? trim((string)$data['ns']) : '';
if (!aiSettingsIsValidNs($ns)) {
    echo json_encode(['success' => false, 'error' => 'Unknown AI settings namespace']);
    exit;
}

try {
    $conn = connectToDatabase();

    // Spends money against the provider, so it belongs to whoever administers this
    // namespace's AI settings — a converted module's capability, else is_admin.
    requireAiNamespaceJson($conn, $ns);

    $cfg  = aiSettingsLoad($conn, $ns);
    if (!aiConfigured($cfg)) {
        echo json_encode(['success' => false, 'error' => $cfg['provider'] === 'openai_compatible'
            ? 'No base URL and model saved yet - save them first.'
            : 'No API key saved yet — save a key first.']);
        exit;
    }

    $result = aiProviderChat($cfg, [
        'system'     => 'You are a connection test. Reply with the single word: OK',
        'user'       => 'Reply with the single word: OK',
        'max_tokens' => 16,
    ]);

    /* GH #192: a custom endpoint answering "OK" proves the address, the key and
       the model - not that the model can do everything a feature asks. Two of the
       features (the war room bot and the Projects assistant) call tools, which
       many local models cannot. So for a custom endpoint the test also asks for
       one tool call and says plainly what came back, rather than letting a
       green "Connection OK" imply every feature will work. */
    $tools = null;
    if ($cfg['provider'] === 'openai_compatible') {
        try {
            $probe = aiProviderChatTools($cfg, [
                'system' => 'You are a connection test. Call the ping tool once, then reply with the single word: OK',
                'user' => 'Call the ping tool.',
                'max_rounds' => 2,
                'max_tokens' => 64,
            ], [['name' => 'ping', 'description' => 'Answers pong. Call it once.', 'schema' => ['type' => 'object', 'properties' => (object)[]]]],
            function () { return 'pong'; });
            $tools = $probe['calls'] ? 'supported' : 'not_used';
        } catch (Exception $te) {
            $tools = 'unsupported';
            $toolsHint = $te->getMessage();   // the server's own words; the panel says which features need tools
        }
    }

    echo json_encode([
        'success'     => true,
        'tools'       => $tools,
        'tools_hint'  => $toolsHint ?? '',
        'provider'    => $result['provider'],
        'model'       => $result['model'],
        'duration_ms' => $result['duration_ms'],
        'reply'       => mb_substr((string)$result['content'], 0, 80),
    ]);
} catch (Exception $e) {
    /* GH #171: a bare "Resource not found (HTTP 404)" left the administrator
       nowhere to go. Say what it most likely means, and - debug tools being
       administrators-only - point an administrator at the tool that shows the
       whole exchange. */
    $out = ['success' => false, 'error' => $e->getMessage()];
    if (isset($cfg)) {
        $hint = aiExplainFailure($cfg, $e->getMessage());
        if ($hint !== '') $out['hint'] = $hint;
    }
    if (sessionIsAdmin()) {
        $out['debug_url'] = BASE_URL . 'system/debug-tools/d014/?ns=' . rawurlencode($ns);
    }
    echo json_encode($out);
}

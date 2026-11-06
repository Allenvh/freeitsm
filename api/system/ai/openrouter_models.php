<?php
/**
 * API: Return the (cached) OpenRouter model catalogue for the model picker.
 * GET ?refresh=1 forces a re-fetch.
 * Returns: { success, models:[{id,name,context_length,prompt_price,completion_price}], cached_at, stale }
 */
session_start(['read_and_close' => true]);
require_once '../../../config.php';
require_once '../../../includes/functions.php';
require_once '../../../includes/rbac.php';
require_once '../../../includes/settings_keys.php';
require_once '../../../includes/ai_provider.php';

// The model list the AI settings panel needs. It has no namespace of its own, so: anyone
// who can configure at least one module's AI may fetch it. (Was admin-only, which meant a
// delegated AI tab could never populate its dropdown.)
if (!isset($_SESSION['analyst_id'])
    || !analystCanManageAnyAiNamespace(connectToDatabase(), (int) $_SESSION['analyst_id'])) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'You do not have permission to manage AI settings']);
    exit;
}

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

try {
    $conn = connectToDatabase();
    $result = aiProviderListOpenRouterModels($conn, !empty($_GET['refresh']));
    echo json_encode([
        'success'   => true,
        'models'    => $result['models'],
        'cached_at' => $result['cached_at'],
        'stale'     => $result['stale'],
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

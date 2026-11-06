<?php
/**
 * API: Knowledge — analyse the gaps.
 *
 * Reads recently closed tickets, asks the knowledge base "do you already answer
 * this?", and groups the ones it does not into clusters. What the assistant
 * reports is never "this ticket could be an article" — that is unknowable from
 * one ticket — but "you have answered this fourteen times and never written it
 * down", which is a fact about volume and is therefore worth acting on.
 *
 * POST { action: 'status' | 'embed' | 'cluster', batch?: int }
 *
 * Chunked because embedding costs a paid API call per ticket and a 90-day window
 * on a busy desk is thousands of them: the front end loops on 'embed' behind a
 * progress bar, then calls 'cluster' once. Every step is resumable — vectors are
 * cached in knowledge_gap_tickets and survive a closed tab.
 *
 * Thin adapter only. The analysis itself lives in includes/knowledge/gap_analysis.php
 * so it can be run — and tested — without an HTTP request.
 */

session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/rbac.php';
require_once '../../includes/encryption.php';
require_once '../../includes/knowledge/gap_analysis.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('knowledge');
// Embedding tickets spends money in exactly the way re-embedding articles does,
// so it sits behind the same capability rather than inventing a new one.
requireCapabilityJson(Cap::KNOWLEDGE_EMBEDDINGS);

$analystId = (int)$_SESSION['analyst_id'];
$input     = json_decode(file_get_contents('php://input'), true) ?: [];
$action    = (string)($input['action'] ?? 'status');
$batch     = (int)($input['batch'] ?? 20);

try {
    $conn = connectToDatabase();

    if (!writeupSchemaReady($conn)) {
        echo json_encode([
            'success'         => false,
            'needs_db_verify' => true,
            'error'           => 'The assistant needs a database update. Run System → Database Verification, then come back.',
        ]);
        exit;
    }

    $cfg       = writeupSettings($conn);
    $lookback  = (int)$cfg['knowledge_gap_lookback_days'];
    $openaiKey = knowledgeOpenAiKey($conn);

    if ($action === 'status') {
        $counts = gapWindowCounts($conn, $analystId, $lookback);
        echo json_encode([
            'success'        => true,
            'tickets'        => $counts['tickets'],
            'embedded'       => $counts['embedded'],
            'remaining'      => $openaiKey === '' ? 0 : max(0, $counts['tickets'] - $counts['embedded']),
            'lookback_days'  => $lookback,
            'has_openai_key' => $openaiKey !== '',
            // With no key the assistant still runs, matching on wording rather
            // than meaning. Worth saying out loud in the UI rather than quietly
            // producing worse clusters.
            'mode'           => $openaiKey !== '' ? 'meaning' : 'wording',
        ]);
        exit;
    }

    if ($action === 'embed') {
        echo json_encode(['success' => true] + gapEmbedBatch($conn, $analystId, $lookback, $batch, $openaiKey));
        exit;
    }

    if ($action === 'cluster') {
        echo json_encode(['success' => true] + gapAnalyse($conn, $analystId));
        exit;
    }

    echo json_encode(['success' => false, 'error' => 'Unknown action']);

} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

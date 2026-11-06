<?php
/**
 * API: Knowledge — "we don't need an article for this".
 *
 * POST { cluster_id: int, undo?: bool }
 *
 * Dismissing is a real decision, not a snooze: plenty of recurring questions
 * genuinely should not become articles (a weekly report request, a door that
 * needs a facilities visit). The dismissal is stored on the cluster and
 * deliberately survives re-analysis — gapPersistClusters matches clusters by
 * ticket overlap precisely so that a growing cluster does not silently come
 * back after someone has already said no.
 *
 * Undo exists because the assistant will be wrong sometimes and a mis-click
 * should not cost you a finding for ever.
 */

session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/rbac.php';
require_once '../../includes/knowledge/writeup_ai.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('knowledge');

$analystId = (int)$_SESSION['analyst_id'];
$input     = json_decode(file_get_contents('php://input'), true) ?: [];
$clusterId = (int)($input['cluster_id'] ?? 0);
$undo      = !empty($input['undo']);

if ($clusterId <= 0) {
    echo json_encode(['success' => false, 'error' => 'Cluster id required']);
    exit;
}

try {
    $conn = connectToDatabase();

    if (!writeupSchemaReady($conn)) {
        echo json_encode(['success' => false, 'error' => 'Run System → Database Verification first.']);
        exit;
    }

    // A cluster id is one guess away, so the scope check is on the WRITE, not
    // just on the list that produced the id.
    [$tSql, $tArgs] = activeTenantFilter($conn, $analystId, 'c');
    $st = $conn->prepare("SELECT c.id, c.status FROM knowledge_gap_clusters c WHERE c.id = ? {$tSql}");
    $st->execute(array_merge([$clusterId], $tArgs));
    $row = $st->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode(['success' => false, 'error' => 'Not found']);
        exit;
    }

    // Never step on a cluster that has already produced an article — that is a
    // different state with a different meaning, and undoing to 'open' would
    // offer to write something that already exists.
    if ($row['status'] === 'written') {
        echo json_encode(['success' => false, 'error' => 'That one already has an article.']);
        exit;
    }

    if ($undo) {
        $conn->prepare("UPDATE knowledge_gap_clusters SET status='open', dismissed_by_id=NULL, dismissed_datetime=NULL WHERE id=?")
             ->execute([$clusterId]);
    } else {
        $conn->prepare("UPDATE knowledge_gap_clusters SET status='dismissed', dismissed_by_id=?, dismissed_datetime=UTC_TIMESTAMP() WHERE id=?")
             ->execute([$analystId, $clusterId]);
    }

    echo json_encode(['success' => true, 'status' => $undo ? 'open' : 'dismissed']);

} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

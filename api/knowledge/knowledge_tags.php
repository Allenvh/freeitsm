<?php
/**
 * API Endpoint: Get all knowledge base tags with article counts
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/knowledge/visibility.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
// Was reachable by ANY signed-in analyst with no module check at all. Matches the
// list endpoint, which this must agree with (see the count note below).
requireAnyModuleAccessJson(['knowledge', 'tickets', 'lms'], null, 'knowledge');   // owner: GH #217

try {
    $conn = connectToDatabase();

    // article_count must match exactly what knowledge_articles.php returns
    // for the list view — same WHERE clause, including the company scope —
    // otherwise clicking a tag appears to show fewer matches than its sidebar
    // count suggests, and the counts themselves would leak how many articles
    // other companies have.
    // ⚠️ 'unarchived', matching knowledge_articles.php EXACTLY — and it did not.
    // The count required is_published = 1 while the list deliberately SHOWS
    // unpublished drafts (the assistant writes them, and a draft nobody can find
    // is a draft nobody publishes). So a tagged draft was listed but not
    // counted, and clicking a tag returned MORE articles than its badge claimed.
    // The comment above already said the two must share a clause; now they
    // literally do, because both ask this one function for it.
    $viewer = KnowledgeViewer::forAnalyst($conn, (int)$_SESSION['analyst_id']);
    [$visSql, $visParams] = knowledgeVisibilitySql($conn, $viewer, 'ka', ['lifecycle' => 'unarchived']);

    $sql = "SELECT t.id, t.name,
                   (SELECT COUNT(*) FROM knowledge_article_tags kat
                    INNER JOIN knowledge_articles ka ON ka.id = kat.article_id
                    WHERE kat.tag_id = t.id
                      $visSql) as article_count
            FROM knowledge_tags t
            ORDER BY t.name";

    $stmt = $conn->prepare($sql);
    $stmt->execute($visParams);
    $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'tags' => $tags
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

?>

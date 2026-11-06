<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Search must not return a document you cannot see (discussion #76).
 *
 * ⚠️ THIS IS THE ONE THAT MATTERS. A corpus row carries a tenant and an internal
 * flag, which is enough to judge a ticket. It is NOT enough to judge a document,
 * whose visibility lives in other tables entirely — so a document row has to
 * satisfy the same at-least-one-visible-parent rule the download endpoint applies.
 *
 * Every denial is paired with a POSITIVE CONTROL: the same analyst, the same
 * document, the module granted. A test that only proves absence passes just as
 * happily when the index is empty, the word is below the full-text minimum, or
 * the query is broken.
 *
 * Run:  php tests/document-search-permissions.php
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/search/search.php';
require_once __DIR__ . '/../includes/search/documents_index.php';

$conn = connectToDatabase();

$pass = 0; $fail = 0;
function check($label, $got, $want) {
    global $pass, $fail;
    if ($got === $want) { $pass++; echo "  ok   $label\n"; }
    else { $fail++; echo "  FAIL $label\n         got:  " . var_export($got, true)
                       . "\n         want: " . var_export($want, true) . "\n"; }
}

/** Does a corpus search as this analyst return our document? */
function searchFinds(PDO $conn, int $analystId, ?array $modules, string $term, int $docId): bool {
    $scope = searchScopeForAnalyst($conn, $analystId, [
        'allowed_modules' => $modules,
        'require_ticket'  => false,          // documents hang off no ticket
    ]);
    $res = searchCorpusQuery($conn, $term, $scope, ['limit' => 50]);
    if (empty($res['ok'])) return false;
    // Results are GROUPED — a result is a ticket (or a standalone row) and the
    // matching corpus rows are under 'hits'. Reading source_type off the group
    // finds nothing and looks exactly like a working permission check, which is
    // why every denial here is paired with a positive control.
    foreach (($res['results'] ?? []) as $group) {
        foreach (($group['hits'] ?? []) as $h) {
            if (($h['source_type'] ?? '') === SEARCH_SOURCE_DOCUMENT && (int)($h['source_id'] ?? 0) === $docId) {
                return true;
            }
        }
    }
    return false;
}

$analyst = (int) $conn->query("SELECT id FROM analysts ORDER BY id LIMIT 1")->fetchColumn();
$term    = 'zzqxwordfortesting';
$docId   = 0; $contractId = 0;

try {
    // Committed rather than rolled back: FULLTEXT is what is being exercised, and
    // the point is to search the real index. Cleaned up in the finally.
    $conn->prepare("INSERT INTO contracts (title) VALUES ('ZZ search-acl contract')")->execute();
    $contractId = (int) $conn->lastInsertId();

    $conn->prepare("INSERT INTO documents (kind, title, description, uploaded_by_id)
                    VALUES ('link','ZZ search acl doc', ?, ?)")
         ->execute(['A description containing ' . $term . ' for the test', $analyst]);
    $docId = (int) $conn->lastInsertId();

    $conn->prepare("INSERT INTO document_links (document_id, parent_type, parent_id, linked_by_id)
                    VALUES (?, 'contract', ?, ?)")->execute([$docId, $contractId, $analyst]);

    searchIndexDocument($conn, $docId);

    echo "The document is in the index and findable at all\n";
    check('unrestricted analyst finds it', searchFinds($conn, $analyst, null, $term, $docId), true);

    echo "\nWithout the parent's module, search must not return it\n";
    check('no "contracts" -> not in results',
        searchFinds($conn, $analyst, ['tickets', 'assets'], $term, $docId), false);
    check('POSITIVE CONTROL: grant "contracts" -> back in results',
        searchFinds($conn, $analyst, ['tickets', 'contracts'], $term, $docId), true);

    echo "\nAn analyst with no modules sees nothing\n";
    check('empty module list -> not in results',
        searchFinds($conn, $analyst, [], $term, $docId), false);

    echo "\nSearch and download agree — the whole point\n";
    foreach ([[null, true], [['contracts'], true], [['tickets'], false], [[], false]] as $case) {
        list($mods, $expected) = $case;
        $inSearch   = searchFinds($conn, $analyst, $mods, $term, $docId);
        $canDownload = documentCanView($conn, $analyst, $mods, $docId);
        check('modules=' . json_encode($mods) . ' search=' . var_export($inSearch, true)
              . ' download=' . var_export($canDownload, true) . ' agree',
              $inSearch === $canDownload, true);
    }

    echo "\nA document whose last link went is removed from the index\n";
    $conn->prepare("DELETE FROM document_links WHERE document_id = ?")->execute([$docId]);
    $conn->prepare("UPDATE documents SET deleted_datetime = UTC_TIMESTAMP() WHERE id = ?")->execute([$docId]);
    searchIndexDocument($conn, $docId);          // re-index: it should remove itself
    check('orphaned + deleted -> gone from search',
        searchFinds($conn, $analyst, null, $term, $docId), false);

} catch (Throwable $e) {
    echo "\nEXCEPTION: " . $e->getMessage() . "\n";
    $fail++;
} finally {
    if ($docId)      {
        $conn->prepare("DELETE FROM search_documents WHERE source_type='document' AND source_id=?")->execute([$docId]);
        $conn->prepare("DELETE FROM document_links WHERE document_id=?")->execute([$docId]);
        $conn->prepare("DELETE FROM documents WHERE id=?")->execute([$docId]);
    }
    if ($contractId) { $conn->prepare("DELETE FROM contracts WHERE id=?")->execute([$contractId]); }
}

echo "\n$pass passed, $fail failed\n";
exit($fail === 0 ? 0 : 1);

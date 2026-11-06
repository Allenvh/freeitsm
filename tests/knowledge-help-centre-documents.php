<?php
/* 🔴 NEVER OVER THE WEB. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Documents on knowledge articles in the self-service Help Centre (GH #177).
 *
 *   php tests/knowledge-help-centre-documents.php
 *
 * The rule (includes/knowledge/help_centre_documents.php): a document attached
 * to an article can be downloaded from the Help Centre by anyone who may read
 * that article there, unless that attachment is Hidden. Every refusal is the
 * same "nothing" so ids cannot be probed.
 *
 * Checked through the same functions the portal endpoints call -
 * helpCentreDocumentForPortalUser() decides get_article_document.php,
 * helpCentreArticleDocuments() fills get_knowledge_article.php - as a throwaway
 * portal requester, against throwaway articles, documents and links (all named
 * "zz-test-hc"), deleted by id in a finally block and checked gone. A real file
 * is written to document storage and removed. Nothing else is touched.
 *
 * Before Database Verification has added document_links.show_in_help_centre it
 * runs the "before verification" checks instead: documents still attach, and
 * the Help Centre offers nothing. Both states matter - every upgraded install is
 * in the first one until somebody runs verification.
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/tenancy.php';
require_once __DIR__ . '/../includes/documents.php';
require_once __DIR__ . '/../includes/knowledge/help_centre_documents.php';

$pass = 0; $fail = 0;
/**
 * Records and prints one PASS or FAIL line.
 *
 * @param string $what  what is being checked, in a few words
 * @param bool $cond  true when the check passed
 * @param string $detail  shown after a failure - the values that disagreed
 */
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}

echo "\nDocuments on knowledge articles in the Help Centre (GH #177)\n" . str_repeat('=', 70) . "\n";
$conn  = connectToDatabase();
$ready = documentHelpCentreReady($conn);
echo $ready ? "  (column present - full checks)\n" : "  (column NOT present - the before-verification checks)\n";

$default = getDefaultTenantId($conn);
$other   = (int) $conn->query("SELECT id FROM tenants WHERE id <> " . (int)$default . " ORDER BY id LIMIT 1")->fetchColumn();
$multi   = isMultiTenant($conn) && $other > 0;
$author  = (int) $conn->query("SELECT id FROM analysts ORDER BY id LIMIT 1")->fetchColumn();

$made = ['articles' => [], 'documents' => [], 'user' => 0, 'files' => []];
try {
    // ---- fixtures ----------------------------------------------------------
    $conn->prepare("INSERT INTO users (email, display_name, tenant_id, is_active) VALUES ('zz-test-hc@invalid.test', 'zz-test-hc', ?, 1)")
         ->execute([$default]);
    $user = $made['user'] = (int) $conn->lastInsertId();

    $article = function (string $label, string $audience, int $published, int $archived, ?int $tenant) use ($conn, $author, &$made): int {
        $conn->prepare("INSERT INTO knowledge_articles (title, body, author_id, audience, is_published, is_archived, tenant_id)
                        VALUES (?, '<p>zz-test-hc</p>', ?, ?, ?, ?, ?)")
             ->execute(["zz-test-hc $label", $author, $audience, $published, $archived, $tenant]);
        return $made['articles'][] = (int) $conn->lastInsertId();
    };
    $doc = function (string $label) use ($conn, &$made): int {
        $key = 'zz-test-hc-' . bin2hex(random_bytes(6)) . '.txt';
        @mkdir(dirname(documentStoragePath($key)), 0775, true);
        file_put_contents(documentStoragePath($key), "zz-test-hc $label");
        $made['files'][] = documentStoragePath($key);
        $conn->prepare("INSERT INTO documents (kind, title, storage_key, original_name, mime_type, size_bytes)
                        VALUES ('file', ?, ?, ?, 'text/plain', 20)")
             ->execute(["zz-test-hc $label", $key, "$label.txt"]);
        return $made['documents'][] = (int) $conn->lastInsertId();
    };
    $can = fn(int $d): bool => helpCentreDocumentForPortalUser($conn, $user, $d) !== null;

    $aCust = $article('customer', 'customer', 1, 0, null);   // shared with every company

    if (!$ready) {
        // ---- before verification ------------------------------------------
        $d = $doc('before');
        $err = null;
        try { documentInsertLink($conn, $d, 'knowledge_article', $aCust, $author); } catch (Throwable $e) { $err = $e->getMessage(); }
        ok('a document still attaches to an article before verification', $err === null, (string) $err);
        ok('the Help Centre lists nothing before verification', helpCentreArticleDocuments($conn, $aCust) === []);
        ok('and serves nothing before verification', !$can($d));
        echo "\n  Run System -> Database Verification, then run this again for the full checks.\n";
    } else {
        // ---- the defaults ------------------------------------------------------
        ok('a new link to an article starts Shown', documentLinkHelpCentreDefault('knowledge_article') === 1);
        ok('a new link to anything else starts not shown', documentLinkHelpCentreDefault('contract') === 0
            && documentLinkHelpCentreDefault('ticket_note') === 0);

        $aInt   = $article('internal', 'internal', 1, 0, null);
        $aDraft = $article('unpublished', 'customer', 0, 0, null);
        $aBin   = $article('archived', 'customer', 1, 1, null);
        $aPub   = $article('public', 'public', 1, 0, null);

        $dShown = $doc('shown');       documentInsertLink($conn, $dShown, 'knowledge_article', $aCust, $author);
        $dHid   = $doc('hidden');      documentInsertLink($conn, $dHid,   'knowledge_article', $aCust, $author);
        helpCentreSetShown($conn, $dHid, $aCust, false);
        $dInt   = $doc('internal');    documentInsertLink($conn, $dInt,   'knowledge_article', $aInt, $author);
        $dDraft = $doc('unpublished'); documentInsertLink($conn, $dDraft, 'knowledge_article', $aDraft, $author);
        $dBin   = $doc('archived');    documentInsertLink($conn, $dBin,   'knowledge_article', $aBin, $author);
        $dPub   = $doc('public');      documentInsertLink($conn, $dPub,   'knowledge_article', $aPub, $author);
        $dCon   = $doc('contract');    documentInsertLink($conn, $dCon,   'contract', 999999999, $author);
        // Not on an article at all, but "shown" set by hand on its link - the flag
        // must never matter outside a knowledge_article.
        $conn->prepare("UPDATE document_links SET show_in_help_centre = 1 WHERE document_id = ?")->execute([$dCon]);
        // One file, two pins: hidden on the customer article, shown on the internal one.
        $dTwo   = $doc('two pins');
        documentInsertLink($conn, $dTwo, 'knowledge_article', $aCust, $author);
        documentInsertLink($conn, $dTwo, 'knowledge_article', $aInt, $author);
        helpCentreSetShown($conn, $dTwo, $aCust, false);

        // ---- downloads -------------------------------------------------------
        $got = helpCentreDocumentForPortalUser($conn, $user, $dShown);
        ok('positive control: a shown document on a customer article downloads',
           $got !== null && is_file(documentStoragePath((string) $got['storage_key']))
           && file_get_contents(documentStoragePath((string) $got['storage_key'])) === 'zz-test-hc shown');
        ok('a public article\'s shown document downloads too', $can($dPub));
        ok('refused: the same article, document set to Hidden', !$can($dHid));
        ok('refused: an article only analysts can read', !$can($dInt));
        ok('refused: an unpublished article', !$can($dDraft));
        ok('refused: an archived article', !$can($dBin));
        ok('refused: a document only on a contract, even with the flag set', !$can($dCon));
        ok('refused: a document id that does not exist', !$can(2147483000));
        ok('two pins: hidden on the readable article, shown on an unreadable one - refused', !$can($dTwo));
        helpCentreSetShown($conn, $dTwo, $aCust, true);
        ok('two pins: show it on the readable article - now downloads', $can($dTwo));
        ok('two pins: the other pin is untouched', (int) $conn->query("SELECT show_in_help_centre FROM document_links
            WHERE document_id = $dTwo AND parent_id = $aInt")->fetchColumn() === 1);

        // ---- what the article page lists ---------------------------------------
        $listed = array_column(helpCentreArticleDocuments($conn, $aCust), 'id');
        sort($listed);
        $want = [$dShown, $dTwo]; sort($want);
        ok('the article lists exactly its shown documents', $listed === $want, 'listed ' . implode(',', $listed));
        $counts = helpCentreDocumentCounts($conn, [$aCust, $aInt]);
        ok('the list\'s "N documents" counts shown ones only (2 shown, 1 hidden)', ($counts[$aCust] ?? 0) === 2,
           json_encode($counts));

        // ---- the article moves out of reach ----------------------------------------
        $conn->prepare("UPDATE knowledge_articles SET audience = 'internal' WHERE id = ?")->execute([$aCust]);
        ok('set the article back to analysts only - its shown document goes with it', !$can($dShown));
        $conn->prepare("UPDATE knowledge_articles SET audience = 'customer' WHERE id = ?")->execute([$aCust]);
        ok('and opened again - back', $can($dShown));

        // ---- companies ---------------------------------------------------------
        if ($multi) {
            $aOther = $article('other company', 'customer', 1, 0, $other);
            $dOther = $doc('other company'); documentInsertLink($conn, $dOther, 'knowledge_article', $aOther, $author);
            ok('refused: a shown document on another company\'s article', !$can($dOther));
            $aMine = $article('my company', 'customer', 1, 0, $default);
            $dMine = $doc('my company'); documentInsertLink($conn, $dMine, 'knowledge_article', $aMine, $author);
            ok('positive control: a shown document on my company\'s article downloads', $can($dMine));
        } else {
            echo "  SKIP  company checks - single-company install\n";
        }

        // ---- a requester who has left ------------------------------------------
        $conn->prepare("UPDATE users SET is_active = 0 WHERE id = ?")->execute([$user]);
        ok('a requester marked as left is refused by the endpoint\'s first check', !requesterIsActive($conn, $user));
    }
} finally {
    foreach ($made['documents'] as $d) {
        $conn->prepare("DELETE FROM document_links WHERE document_id = ?")->execute([$d]);
        $conn->prepare("DELETE FROM documents WHERE id = ? AND title LIKE 'zz-test-hc%'")->execute([$d]);
    }
    foreach ($made['articles'] as $a) {
        $conn->prepare("DELETE FROM knowledge_articles WHERE id = ? AND title LIKE 'zz-test-hc%'")->execute([$a]);
    }
    if ($made['user']) $conn->prepare("DELETE FROM users WHERE id = ? AND email = 'zz-test-hc@invalid.test'")->execute([$made['user']]);
    foreach ($made['files'] as $f) @unlink($f);
}
$left = (int) $conn->query("SELECT (SELECT COUNT(*) FROM documents WHERE title LIKE 'zz-test-hc%')
                                 + (SELECT COUNT(*) FROM knowledge_articles WHERE title LIKE 'zz-test-hc%')
                                 + (SELECT COUNT(*) FROM users WHERE email = 'zz-test-hc@invalid.test')")->fetchColumn();
$filesLeft = count(array_filter($made['files'], 'is_file'));
ok('cleanup: every throwaway row and file is gone', $left === 0 && $filesLeft === 0, "{$left} rows, {$filesLeft} files left");

echo "\n{$pass} passed, {$fail} failed\n";
exit($fail ? 1 : 0);

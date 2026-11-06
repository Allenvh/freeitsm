<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Document visibility — a document is visible iff you can see something it is
 * attached to (GH discussion #76).
 *
 * ⚠️ EVERY "cannot see" ASSERTION IS PAIRED WITH A POSITIVE CONTROL. A test that
 * only ever proves absence passes just as happily when the query is broken, the
 * table is empty, or the filter excludes everything. Each denial below is
 * followed by the same analyst being granted the parent and seeing the document,
 * or the assertion proves nothing.
 *
 * Two shapes of the same question are tested, because the product asks it twice:
 *   documentVisibilityClause() — the SET, used by search
 *   documentCanView()          — the ROW, used by download
 * They must always agree. A document you cannot find but CAN download is the
 * whole bug this design exists to prevent.
 *
 * Writes to a scratch tenant/records inside a transaction and rolls back.
 *
 * Run:  php tests/document-permissions.php
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/documents.php';

$conn = connectToDatabase();

$pass = 0; $fail = 0;
function check($label, $got, $want) {
    global $pass, $fail;
    if ($got === $want) { $pass++; echo "  ok   $label\n"; }
    else { $fail++; echo "  FAIL $label\n         got:  " . var_export($got, true)
                       . "\n         want: " . var_export($want, true) . "\n"; }
}

/** Does the SET query return this document for this analyst? */
function setSees(PDO $conn, int $analystId, ?array $modules, int $docId): bool {
    list($sql, $params) = documentVisibilityClause($conn, $analystId, $modules, 'd');
    $st = $conn->prepare("SELECT COUNT(*) FROM documents d WHERE d.id = ?" . $sql);
    $st->execute(array_merge([$docId], $params));
    return (int) $st->fetchColumn() > 0;
}

$conn->beginTransaction();
try {
    $analyst = (int) $conn->query("SELECT id FROM analysts ORDER BY id LIMIT 1")->fetchColumn();
    if (!$analyst) { exit("No analyst to test with.\n"); }

    // A parent of each kind we can create cheaply and roll back.
    $conn->prepare("INSERT INTO contracts (title) VALUES ('ZZ doc-test contract')")->execute();
    $contractId = (int) $conn->lastInsertId();

    $conn->prepare("INSERT INTO documents (kind, title, description, uploaded_by_id)
                    VALUES ('file', 'ZZ doc-test warranty', 'test', ?)")->execute([$analyst]);
    $docId = (int) $conn->lastInsertId();

    $conn->prepare("INSERT INTO document_links (document_id, parent_type, parent_id, linked_by_id)
                    VALUES (?, 'contract', ?, ?)")->execute([$docId, $contractId, $analyst]);

    echo "The rule: visible iff you can see something it is attached to\n";
    check('unrestricted analyst sees it',
        setSees($conn, $analyst, null, $docId), true);
    check('  ...and may download it',
        documentCanView($conn, $analyst, null, $docId), true);

    echo "\nNo access to the parent's module = invisible\n";
    check('without "contracts", the SET hides it',
        setSees($conn, $analyst, ['tickets', 'assets'], $docId), false);
    check('without "contracts", download is refused',
        documentCanView($conn, $analyst, ['tickets', 'assets'], $docId), false);
    // POSITIVE CONTROL — the same analyst, the same document, module granted.
    check('POSITIVE CONTROL: grant "contracts" and it appears',
        setSees($conn, $analyst, ['tickets', 'contracts'], $docId), true);
    check('POSITIVE CONTROL: grant "contracts" and download is allowed',
        documentCanView($conn, $analyst, ['tickets', 'contracts'], $docId), true);

    echo "\nAn analyst with no modules at all sees nothing (fails CLOSED)\n";
    check('empty module list hides it',
        setSees($conn, $analyst, [], $docId), false);
    list($sqlNone, ) = documentVisibilityClause($conn, $analyst, [], 'd');
    check('  ...and the clause is 0=1, not an empty string',
        trim($sqlNone), 'AND 0=1');

    echo "\nA second parent grants access on its own (Ed's rule, multi-parent)\n";
    $conn->prepare("INSERT INTO document_links (document_id, parent_type, parent_id, linked_by_id)
                    VALUES (?, 'ticket', ?, ?)")
         ->execute([$docId, (int) $conn->query("SELECT id FROM tickets WHERE deleted_datetime IS NULL ORDER BY id LIMIT 1")->fetchColumn(), $analyst]);
    check('tickets-only analyst now sees it via the ticket',
        setSees($conn, $analyst, ['tickets'], $docId), true);
    check('  ...and may download it',
        documentCanView($conn, $analyst, ['tickets'], $docId), true);

    echo "\nRemoving the links removes the access (an orphan belongs to nobody)\n";
    $conn->prepare("DELETE FROM document_links WHERE document_id = ?")->execute([$docId]);
    check('orphan is invisible to the SET',
        setSees($conn, $analyst, null, $docId), false);
    check('orphan cannot be downloaded',
        documentCanView($conn, $analyst, null, $docId), false);
    // POSITIVE CONTROL — re-link and it comes back, so the two above are not
    // passing merely because the document row went missing.
    $conn->prepare("INSERT INTO document_links (document_id, parent_type, parent_id, linked_by_id)
                    VALUES (?, 'contract', ?, ?)")->execute([$docId, $contractId, $analyst]);
    check('POSITIVE CONTROL: re-link and it returns',
        setSees($conn, $analyst, null, $docId), true);

    echo "\nA deleted parent stops granting access\n";
    $conn->prepare("DELETE FROM contracts WHERE id = ?")->execute([$contractId]);
    check('document with only a deleted parent is invisible',
        setSees($conn, $analyst, ['contracts'], $docId), false);
    check('  ...and cannot be downloaded',
        documentCanView($conn, $analyst, ['contracts'], $docId), false);

    echo "\nThe SET and the ROW never disagree\n";
    $cases = [null, ['contracts'], ['tickets'], [], ['assets']];
    $agree = true;
    foreach ($cases as $mods) {
        if (setSees($conn, $analyst, $mods, $docId) !== documentCanView($conn, $analyst, $mods, $docId)) {
            $agree = false;
        }
    }
    check('search and download agree in every case', $agree, true);

    echo "\nUnknown entity types are refused, not ignored\n";
    check('an unregistered parent type grants nothing',
        documentCanViewParent($conn, $analyst, null, 'not_a_real_type', 1), false);

    // ⚠️ document_links.parent_id is POLYMORPHIC, so no foreign key can protect
    // it. Deleting a contract leaves the link behind and nothing objects. The
    // document is invisible from that instant, but without a sweep it is never
    // collected and its file stays on disk for ever.
    echo "\nA deleted PARENT must not leave the document stranded\n";
    $conn->prepare("INSERT INTO contracts (title) VALUES ('ZZ orphan-sweep')")->execute();
    $cid2 = (int) $conn->lastInsertId();
    $conn->prepare("INSERT INTO documents (kind,title,uploaded_by_id) VALUES ('link','ZZ orphan-sweep-doc',?)")
         ->execute([$analyst]);
    $did2 = (int) $conn->lastInsertId();
    $conn->prepare("INSERT INTO document_links (document_id,parent_type,parent_id,linked_by_id) VALUES (?,'contract',?,?)")
         ->execute([$did2, $cid2, $analyst]);
    $conn->prepare("DELETE FROM contracts WHERE id = ?")->execute([$cid2]);

    $st = $conn->prepare("SELECT COUNT(*) FROM document_links WHERE document_id = ?");
    $st->execute([$did2]);
    check('the link survives the parent — nothing in the database stops it',
        (int) $st->fetchColumn(), 1);
    check('  ...but the document is already invisible, so nothing leaks',
        setSees($conn, $analyst, null, $did2), false);

    $swept = documentsCollectOrphans($conn, 100);
    // ⚠️ The first version of the sweep used a multi-table DELETE with LIMIT,
    // which MySQL rejects. The error was caught and logged, and it reported
    // "0 removed" — a broken sweep that looked exactly like a clean one. Assert
    // there were no errors, not merely that the counts came back.
    check('the sweep reports no errors', empty($swept['errors']), true);

    $st->execute([$did2]);
    check('the dead link is removed', (int) $st->fetchColumn(), 0);
    $g = $conn->prepare("SELECT deleted_datetime IS NOT NULL FROM documents WHERE id = ?");
    $g->execute([$did2]);
    check('the document is collected', (bool) $g->fetchColumn(), true);

    $conn->rollBack();
} catch (Throwable $e) {
    if ($conn->inTransaction()) $conn->rollBack();
    echo "\nEXCEPTION: " . $e->getMessage() . "\n";
    $fail++;
}

echo "\n$pass passed, $fail failed\n";
exit($fail === 0 ? 0 : 1);

<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * The search function — query translation, scope enforcement, result shape.
 *
 * Search has a failure mode that ordinary features do not: it can be WRONG and
 * look RIGHT. A permission filter that quietly does nothing returns a page full
 * of plausible results. A query that matches nothing looks identical to a
 * genuine "not in your tickets". So almost every assertion here is paired with a
 * control proving the check could have failed.
 *
 * Three things it pins:
 *
 *  1. QUERY TRANSLATION. What a person types is not what MySQL is asked. Terms
 *     too short to be indexed must be DROPPED, because requiring one in boolean
 *     mode makes the whole query match nothing — the difference between "search
 *     works" and "search mysteriously returns nothing for that phrase".
 *
 *  2. SCOPE GOES INTO THE QUERY. Every "it was excluded" assertion has a twin
 *     showing the same row IS returned once the predicate is relaxed. Without
 *     that twin, a filter that matched nothing at all would pass.
 *
 *  3. THE RESULT SHAPE. Hits collapse to their ticket and report which parts
 *     matched, because a user thinks in tickets and one ticket with the term in
 *     four replies must not flood the page.
 *
 *   php tests/search/run.php
 *
 * ⚠️ Writes a handful of rows with a reserved source_type and deletes them in a
 * finally block. It cannot use a rolled-back transaction: InnoDB does not expose
 * uncommitted rows to MATCH...AGAINST.
 */

require_once __DIR__ . '/../../includes/search/search.php';

$pass = $fail = 0;
function ok(string $what, bool $cond) {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  ok    $what\n"; }
    else       { $fail++; echo "  FAIL  $what\n"; }
}
function section(string $s) { echo "\n$s\n" . str_repeat('-', strlen($s)) . "\n"; }

// ===========================================================================
section('1. Query translation (no database needed)');

$p = searchParseQuery('printer jam', 3);
ok("plain words become required terms with a stemming wildcard", $p['expr'] === '+printer* +jam*');

$p = searchParseQuery('"tower of london" castle', 3);
ok("a quoted phrase survives as a phrase", $p['expr'] === '+"tower of london" +castle*');
ok("...and the loose word is still required",  in_array('castle', $p['terms'], true));

$p = searchParseQuery('printer -jam', 3);
ok("a leading minus excludes",                 $p['expr'] === '+printer* -jam');

$p = searchParseQuery('a in of', 3);
ok("terms below the index minimum are DROPPED, not required", $p['expr'] === '');
ok("...and are reported so the caller can say so",            $p['dropped'] === ['a','in','of']);

// ⚠️ The trap this guards: passing a too-short term through as "+in" makes the
// ENTIRE query match nothing, because the word is not in the index at all.
ok("CONTROL — requiring a dropped term would have produced a query that matches nothing",
   searchParseQuery('tower in london', 3)['expr'] === '+tower* +london*');

$p = searchParseQuery('tower +++ ~~~ london', 3);
ok("boolean operators typed by a user are stripped, not honoured", $p['expr'] === '+tower* +london*');


// --- Punctuation inside a word (GH #102) -----------------------------------
// The value 05145-8841815 in an attachment is indexed by MySQL as TWO tokens,
// because MySQL splits on punctuation. Deleting the punctuation here glued them
// into "051458841815", which appears nowhere — so the search silently found
// nothing while the same value typed with a space found it at once.
$p = searchParseQuery('05145-8841815', 3);
ok("a hyphenated value SPLITS into the tokens MySQL indexed",
   $p['expr'] === '+05145* +8841815*');
ok("...and is NOT glued into one token that was never indexed",
   !str_contains($p['expr'], '051458841815'));

ok("a hyphenated WORD splits the same way",
   searchParseQuery('pre-flight', 3)['expr'] === '+pre* +flight*');

ok("every separator behaves alike — dot, comma, slash, colon",
   searchParseQuery('05145.884', 3)['expr'] === '+05145* +884*'
   && searchParseQuery('05145,884', 3)['expr'] === '+05145* +884*'
   && searchParseQuery('05145/884', 3)['expr'] === '+05145* +884*'
   && searchParseQuery('05145:884', 3)['expr'] === '+05145* +884*');

ok("a hyphenated word reads the same as typing it with a space",
   searchParseQuery('pre-flight', 3)['expr'] === searchParseQuery('pre flight', 3)['expr']);

// The LEADING minus is still the user's exclusion operator, and now applies to
// every part of the word it is attached to.
$p = searchParseQuery('printer -pre-flight', 3);
ok("a leading minus still excludes, across the whole hyphenated word",
   $p['expr'] === '+printer* -pre -flight');

// A separator must not smuggle a boolean operator into the expression.
ok("operators embedded mid-word are separators, not operators",
   searchParseQuery('tower+london', 3)['expr'] === '+tower* +london*');

// Short pieces are still dropped rather than required — requiring a token below
// the index minimum makes the WHOLE query match nothing.
$p = searchParseQuery('B4-05145', 3);
ok("a piece below the index minimum is dropped, the usable one survives",
   $p['expr'] === '+05145*' && $p['dropped'] === ['B4']);
ok("an empty query yields an empty expression", searchParseQuery('   ', 3)['expr'] === '');

// ===========================================================================
section('2. Scope becomes SQL — and only here');

[$sql, $args] = searchScopeToSql(['tenant_id' => 4, 'include_default' => false], 'sd');
ok("a company scope filters on tenant_id",      str_contains($sql, 'sd.tenant_id = ?'));
ok("...and always lets 'shared' rows through",  str_contains($sql, "sd.tenant_scope = 'shared'"));
ok("...but NOT 'default' when not in the default company", !str_contains($sql, "'default'"));
ok("...binding the id rather than inlining it", $args === [4]);

[$sql2] = searchScopeToSql(['tenant_id' => 1, 'include_default' => true], 'sd');
ok("'default' rows ARE visible from the default company", str_contains($sql2, "sd.tenant_scope = 'default'"));

[$sql3] = searchScopeToSql(['include_internal' => false], 'sd');
ok("excluding internal notes appears in the SQL", str_contains($sql3, 'sd.is_internal = 0'));

[$sql4, $args4] = searchScopeToSql(['source_types' => ['note','email']], 'sd');
ok("a source-type filter is parameterised", substr_count($sql4, '?') === 2 && $args4 === ['note','email']);

// ⚠️ An UNSPECIFIED scope must fail CLOSED, not open. Leaving include_internal
// unset hides internal notes rather than exposing them — the safe direction for
// a caller that forgot to say.
[$sql5, $args5] = searchScopeToSql([], 'sd');
ok("an unspecified scope FAILS CLOSED — internal notes hidden by default",
   str_contains($sql5, 'sd.is_internal = 0'));
ok("...and applies no company filter when none was asked for",
   !str_contains($sql5, 'tenant') && $args5 === []);

// CONTROL — the tenant clause really is driven by the scope, not always present.
[$sqlT] = searchScopeToSql(['tenant_id' => 7, 'include_internal' => true], 'sd');
[$sqlN] = searchScopeToSql(['tenant_id' => null, 'include_internal' => true], 'sd');
ok("CONTROL — a tenant clause appears with an id and vanishes without one",
   str_contains($sqlT, 'tenant_id') && $sqlN === '');

// ===========================================================================
section('3. Live search against the real corpus');

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/functions.php';
$conn = connectToDatabase();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (!searchCorpusReady($conn)) {
    echo "  SKIP  search_documents does not exist — run Database Verification first.\n";
    echo "\n" . str_repeat('=', 60) . "\n";
    printf("%d passed, %d failed (live section skipped)\n", $pass, $fail);
    exit($fail === 0 ? 0 : 1);
}

$T = '_test_search';   // reserved: nothing else writes it

// ⚠️ Real ticket ids, because the foreign key correctly refuses invented ones.
// That refusal is the cascade guard working, so the test borrows rather than
// fabricates — and only ever writes corpus rows, never touching the tickets.
// ⚠️ Ticket B must REALLY be in company 4: since the October 2026 security fix,
// search judges a ticket's rows by the ticket's live company, not only the copy
// in the corpus (a moved ticket used to stay findable from its old company). A
// borrowed ticket from another company would - correctly - be hidden from 4.
$b4 = $conn->query("SELECT id FROM tickets WHERE tenant_id = 4 ORDER BY id DESC LIMIT 1")->fetchColumn();
$ids = $b4 === false ? [] : [
    (int)$conn->query("SELECT id FROM tickets WHERE id <> " . (int)$b4 . " AND (tenant_id IS NULL OR tenant_id <> 4) ORDER BY id DESC LIMIT 1")->fetchColumn(),
    (int)$b4,
];
$ids = array_values(array_filter($ids));
if (count($ids) < 2) {
    echo "  SKIP  needs at least two tickets in the database to exercise grouping.\n";
    echo "\n" . str_repeat('=', 60) . "\n";
    printf("%d passed, %d failed (live section skipped)\n", $pass, $fail);
    exit($fail === 0 ? 0 : 1);
}
[$ticketA, $ticketB] = [(int)$ids[0], (int)$ids[1]];

try {
    $conn->prepare("DELETE FROM search_documents WHERE source_type LIKE ?")->execute([$T . '%']);

    // Two "tickets" worth of rows. ticket_id values are deliberately fake — the
    // LEFT JOIN tolerates a missing ticket, which also proves the join does not
    // silently drop rows whose ticket is absent.
    $rows = [
        // type,                 src,  ticket,   tenant, scope,     internal, title,                         body
        [$T.'_ticket',           1, $ticketA, null,   'default', 0, 'Printer on floor two keeps jamming', ''],
        [$T.'_email',            2, $ticketA, null,   'default', 0, 'RE: floor two hardware',             'the duplex unit jams on heavy paper'],
        [$T.'_note',             3, $ticketA, null,   'default', 1, '',                                   'internal only: replaced the fuser assembly'],
        [$T.'_ticket',           4, $ticketB, 4,      'company', 0, 'Laptop battery replacement',         ''],
        [$T.'_email',            5, $ticketB, 4,      'company', 0, 'RE: battery',                        'the battery swells and the laptop will not charge'],
        [$T.'_article',          6, null,     null,   'shared',  0, 'How to replace a laptop battery',    'shared guidance for every company about battery swelling'],
    ];
    $ins = $conn->prepare("INSERT INTO search_documents
        (source_type, source_id, ticket_id, tenant_id, tenant_scope, is_internal, title, body, source_datetime)
        VALUES (?,?,?,?,?,?,?,?,NOW())");
    foreach ($rows as $r) $ins->execute($r);

    // ⚠️ SCOPE EVERY QUERY TO THE FIXTURE'S OWN SOURCE TYPES.
    //
    // Every assertion below is written as though the index contains only the six
    // rows above — "total === 1", "results[0] is mine". That is true on a clean
    // install and false on any install with real indexed content, because the
    // fixtures use ordinary English words. On a populated database a search for
    // 'floor' returned thirteen tickets with the fixture ranked second, and
    // 'guidance -swelling' correctly excluded the fixture but still matched a real
    // ticket reading "Any guidance would…", so the totals were never going to hold.
    //
    // That failed the RIGHT way round only by luck: a suite that passes on an empty
    // database and fails on a real one is a suite whose result depends on whose
    // machine it runs on. source_type is an IN filter (search.php:151), so naming
    // the fixture's types makes the isolation these assertions already assume into
    // something the query actually enforces.
    $mineOnly = [$T.'_ticket', $T.'_email', $T.'_note', $T.'_article'];
    $all = ['tenant_id' => null, 'include_internal' => true, 'include_deleted' => true,
            'source_types' => $mineOnly];
    $only = fn(array $res) => array_map(fn($g) => $g['ticket_id'], $res['results']);

    // --- finding things ---
    $r = searchCorpusQuery($conn, 'jamming', $all);
    ok("finds a word in a ticket subject", $r['ok'] && $r['total'] === 1 && $only($r) === [$ticketA]);

    $r = searchCorpusQuery($conn, 'duplex', $all);
    ok("finds a word in a message body",   $r['ok'] && $r['total'] === 1);

    $r = searchCorpusQuery($conn, 'battery', $all);
    ok("one term matching several rows still collapses to its tickets", $r['total'] === 2);

    $r = searchCorpusQuery($conn, 'zzzabsentzzz', $all);
    ok("CONTROL — a term that is not there returns nothing", $r['ok'] && $r['total'] === 0);

    // --- the result shape ---
    $r = searchCorpusQuery($conn, 'floor', $all);
    $g = $r['results'][0] ?? [];
    ok("a result names WHICH parts of the ticket matched",
        isset($g['matched']) && in_array($T.'_ticket', $g['matched'], true) && in_array($T.'_email', $g['matched'], true));
    ok("...and carries a snippet for each hit", !empty($g['hits'][0]['snippet']) || $g['hits'][0]['title'] !== '');

    // --- scope: internal notes ---
    $r = searchCorpusQuery($conn, 'fuser', $all);
    ok("an internal note IS found when internal notes are allowed", $r['total'] === 1);
    $r = searchCorpusQuery($conn, 'fuser', array_merge($all, ['include_internal' => false]));
    ok("...and is excluded by the predicate when they are not", $r['total'] === 0);

    // --- scope: company ---
    $r = searchCorpusQuery($conn, 'swells', array_merge($all, ['tenant_id' => 4, 'include_default' => false]));
    ok("a company-scoped row is visible from its own company", $r['total'] === 1);
    $r = searchCorpusQuery($conn, 'swells', array_merge($all, ['tenant_id' => 99, 'include_default' => false]));
    ok("...and INVISIBLE from another company", $r['total'] === 0);

    $r = searchCorpusQuery($conn, 'guidance', array_merge($all, ['tenant_id' => 99, 'include_default' => false]));
    ok("a 'shared' row is visible from ANY company", $r['total'] === 1);

    $r = searchCorpusQuery($conn, 'jamming', array_merge($all, ['tenant_id' => 99, 'include_default' => false]));
    ok("a 'default' row is hidden from a non-default company", $r['total'] === 0);
    $r = searchCorpusQuery($conn, 'jamming', array_merge($all, ['tenant_id' => 99, 'include_default' => true]));
    ok("CONTROL — ...and visible again once include_default is set", $r['total'] === 1);

    // --- scope: source types ---
    $r = searchCorpusQuery($conn, 'battery', array_merge($all, ['source_types' => [$T.'_article']]));
    ok("can search JUST one kind of source", $r['total'] === 1 && $r['results'][0]['ticket_id'] === null);

    // --- scope: require_ticket -------------------------------------------------
    // The search-inside-tickets endpoint renders ticket rows and nothing else, so a
    // match with no ticket (an article) was counted in the total and then dropped
    // when the rows were built: "4 tickets found" printed above three of them.
    // The count and the list have to be answering the same question.
    $r = searchCorpusQuery($conn, 'battery', $all);
    $withoutTicket = count(array_filter($r['results'], fn($g) => $g['ticket_id'] === null));
    ok("CONTROL — unfiltered, a non-ticket match IS returned", $withoutTicket >= 1);

    $r = searchCorpusQuery($conn, 'battery', array_merge($all, ['require_ticket' => true]));
    $withoutTicket = count(array_filter($r['results'], fn($g) => $g['ticket_id'] === null));
    ok("require_ticket drops matches that belong to no ticket", $withoutTicket === 0);
    ok("require_ticket keeps the total and the row count in step",
       $r['total'] === count($r['results']));

    // --- query handling ---
    $r = searchCorpusQuery($conn, 'a', $all);
    ok("a query of only unusable terms says so rather than returning 'no results'",
       $r['ok'] === false && $r['reason'] === 'no_usable_terms');

    $r = searchCorpusQuery($conn, '"heavy paper"', $all);
    ok("an exact phrase matches", $r['total'] === 1);
    $r = searchCorpusQuery($conn, '"paper heavy"', $all);
    ok("CONTROL — the same words in the wrong order do NOT match as a phrase", $r['total'] === 0);

    // ⚠️ EXCLUSION IS PER-DOCUMENT, NOT PER-TICKET. "-swells" drops the message
    // that says it, but the ticket survives on its subject row. A ticket only
    // disappears when EVERY one of its matching documents is excluded. That is
    // how document search works, and it is worth knowing before someone reads
    // "battery -swells" as "tickets that never mention swelling".
    $r = searchCorpusQuery($conn, 'battery -swells', $all);
    $ticketBhits = [];
    foreach ($r['results'] as $g) if ($g['ticket_id'] === $ticketB) $ticketBhits = $g['hits'];
    $bodies = implode(' ', array_column($ticketBhits, 'snippet'));
    ok("an exclusion removes the matching DOCUMENT", !str_contains($bodies, 'swells'));
    ok("...but the ticket survives on its other matching document", count($ticketBhits) >= 1);

    $r = searchCorpusQuery($conn, 'guidance -swelling', $all);
    ok("CONTROL — when the ONLY matching document is excluded, the result disappears", $r['total'] === 0);

    // --- paging ---
    $r = searchCorpusQuery($conn, 'battery', $all, ['limit' => 1]);
    ok("limit caps the results but total still reports the truth",
       count($r['results']) === 1 && $r['total'] === 2);

} finally {
    $conn->prepare("DELETE FROM search_documents WHERE source_type LIKE ?")->execute([$T . '%']);
}

$left = (int)$conn->query("SELECT COUNT(*) FROM search_documents WHERE source_type LIKE '_test_search%'")->fetchColumn();
ok("test rows cleaned up", $left === 0);

echo "\n" . str_repeat('=', 60) . "\n";
printf("%d passed, %d failed\n", $pass, $fail);
exit($fail === 0 ? 0 : 1);

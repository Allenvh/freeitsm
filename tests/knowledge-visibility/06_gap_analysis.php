<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * The Knowledge assistant must not judge one company's tickets "already covered"
 * by another company's articles.
 *
 * gapWindowSql() has always company-scoped the TICKET side. The article side was
 * a bare `SELECT ... FROM knowledge_articles WHERE is_published = 1 ...` with no
 * tenant filter at all, so the two halves of the same comparison disagreed about
 * whose data was in scope.
 *
 * WHAT THAT ACTUALLY BROKE — worth being exact, because the obvious guess is
 * wrong. The matched id lands in `knowledge_gap_tickets.best_article_id`, which
 * NOTHING reads, and the article title shown by api/knowledge/gap_clusters.php
 * comes from `knowledge_gap_clusters.article_id` — the article WRITTEN FROM a
 * cluster, not the best match. No foreign title was ever displayed.
 *
 * The real damage is quieter and worse. A ticket scoring above the article bar is
 * dropped from the candidate list and can never become a gap. Scoring against
 * articles the company cannot see therefore made the assistant SILENTLY
 * UNDER-REPORT: real gaps vanished, with no error, no warning, and an "everything
 * is covered" message that reads like good news. The feature exists to say what
 * is missing.
 *
 * Runs entirely inside ONE transaction that is ALWAYS rolled back, and in
 * "wording" mode so it needs no OpenAI key and spends nothing.
 *
 *   php tests/knowledge-visibility/06_gap_analysis.php
 *
 * ⚠️ Every negative assertion is paired with a POSITIVE CONTROL. "It did not match
 * the other company's article" is equally true of a harness that matched nothing
 * at all — so the same fixture proves that an article the analyst CAN see is
 * still matched, and that a SHARED one still is too.
 */

$APP = dirname(__DIR__, 2);
require_once __DIR__ . '/_bootstrap.php';
require_once $APP . '/config.php';
require_once $APP . '/includes/functions.php';
require_once $APP . '/includes/knowledge/gap_analysis.php';

$pass = 0; $fail = 0;
function check(string $what, bool $ok, string $detail = ''): void {
    global $pass, $fail;
    if ($ok) { $pass++; echo "  PASS  $what\n"; }
    else     { $fail++; echo "  FAIL  $what" . ($detail !== '' ? "\n        -> $detail" : '') . "\n"; }
}

$c = connectToDatabase();
$c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (!writeupSchemaReady($c)) {
    fwrite(STDERR, "The gap tables are missing. Run System -> Database Verification first.\n");
    exit(1);
}
if (!isMultiTenant($c)) {
    echo "  SKIP  single-company install: there is no second company to leak from.\n";
    echo "\n0 passed, 0 failed\n";
    exit(0);
}

$closedStatus = (int)$c->query("SELECT id FROM ticket_statuses WHERE is_closed = 1 ORDER BY id LIMIT 1")->fetchColumn();
if (!$closedStatus) { fwrite(STDERR, "No closed ticket status on this install.\n"); exit(1); }

$tenants = $c->query("SELECT id FROM tenants ORDER BY id LIMIT 2")->fetchAll(PDO::FETCH_COLUMN);
if (count($tenants) < 2) { fwrite(STDERR, "Need two companies to run this.\n"); exit(1); }
[$mine, $theirs] = [(int)$tenants[0], (int)$tenants[1]];

$c->beginTransaction();
try {
    // An analyst who can see ONLY $mine.
    $c->prepare("INSERT INTO analysts (username, password_hash, full_name, email, is_active, is_admin, can_access_all_tenants, can_access_all_modules, created_datetime)
                 VALUES ('zz-gap', ?, 'ZZ Gap', 'zz@gap.test', 1, 0, 0, 1, UTC_TIMESTAMP())")
      ->execute([password_hash('x', PASSWORD_DEFAULT)]);
    $analyst = (int)$c->lastInsertId();
    $c->prepare("INSERT INTO analyst_tenant_access (analyst_id, tenant_id) VALUES (?, ?)")->execute([$analyst, $mine]);
    // No session in a CLI harness; getActiveTenantId() reads this superglobal.
    $_SESSION['active_tenant_id'] = $mine;

    // One distinctive title. Wording mode matches on subject-line tokens, so a
    // ticket with this subject matches it strongly whoever owns it — which means
    // the only thing deciding the outcome is which articles the query returned.
    $title = 'ZZGAPWIDGET calibration procedure';
    $mkArticle = function (?int $tenant) use ($c, $analyst, $title) {
        $st = $c->prepare("INSERT INTO knowledge_articles (title, body, author_id, is_published, is_archived, created_datetime, modified_datetime, tenant_id, audience)
                           VALUES (?, '<p>x</p>', ?, 1, 0, UTC_TIMESTAMP(), UTC_TIMESTAMP(), ?, 'internal')");
        $st->execute([$title, $analyst, $tenant]);
        return (int)$c->lastInsertId();
    };

    // Closed tickets in MY company asking exactly what that article answers.
    $mkTicket = function (string $subject) use ($c, $closedStatus, $mine) {
        $st = $c->prepare("INSERT INTO tickets (ticket_number, subject, status_id, tenant_id, created_datetime, closed_datetime)
                           VALUES (?, ?, ?, ?, UTC_TIMESTAMP(), UTC_TIMESTAMP())");
        $st->execute(['ZZGAP-' . bin2hex(random_bytes(6)), $subject, $closedStatus, $mine]);
        return (int)$c->lastInsertId();
    };
    $myTickets = [];
    foreach (['ZZGAPWIDGET calibration procedure',
              'ZZGAPWIDGET calibration procedure please',
              'Re: ZZGAPWIDGET calibration procedure',
              'ZZGAPWIDGET calibration procedure needed'] as $s) {
        $myTickets[] = $mkTicket($s);
    }
    $in = implode(',', array_fill(0, count($myTickets), '?'));

    /** What the analysis decided each of MY tickets was best answered by. */
    $matches = function () use ($c, $myTickets, $in) {
        $st = $c->prepare("SELECT ticket_id, best_article_id FROM knowledge_gap_tickets WHERE ticket_id IN ($in)");
        $st->execute($myTickets);
        $out = [];
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) {
            $out[(int)$r['ticket_id']] = $r['best_article_id'] === null ? null : (int)$r['best_article_id'];
        }
        return $out;
    };
    $run = function () use ($c, $analyst) {
        return gapAnalyse($c, $analyst, ['force_wording' => true, 'lookback_days' => 3650]);
    };

    echo "=== only the OTHER company owns a matching article ===\n";
    $theirArticle = $mkArticle($theirs);
    $run();
    $m = $matches();
    check('every one of my tickets was analysed',
          count($m) === count($myTickets),
          'analysed ' . count($m) . ' of ' . count($myTickets) . ' - the fixture never reached the analysis');
    check("NONE of my tickets was matched to the other company's article (#$theirArticle)",
          !in_array($theirArticle, $m, true),
          'matches: ' . json_encode($m) . ' - my tickets were scored against a company I cannot see');

    echo "\n=== POSITIVE CONTROL: my own company owns the same article ===\n";
    $myArticle = $mkArticle($mine);
    $run();
    $m = $matches();
    check("my tickets ARE matched to my own article (#$myArticle)",
          in_array($myArticle, $m, true),
          'matches: ' . json_encode($m) . ' - nothing matched at all, so the assertion above proves nothing');
    check("the other company's article is STILL never matched",
          !in_array($theirArticle, $m, true),
          'matches: ' . json_encode($m));

    echo "\n=== POSITIVE CONTROL: a SHARED article (tenant_id IS NULL) still counts ===\n";
    $c->prepare("DELETE FROM knowledge_articles WHERE id = ?")->execute([$myArticle]);
    $sharedArticle = $mkArticle(null);
    $run();
    $m = $matches();
    check("my tickets ARE matched to the shared article (#$sharedArticle)",
          in_array($sharedArticle, $m, true),
          'matches: ' . json_encode($m) . ' - the scope overshot and hid shared articles, which belong to everybody');

    $c->rollBack();
    echo "\nrolled back\n";
} catch (Throwable $e) {
    if ($c->inTransaction()) $c->rollBack();
    echo "  FAIL  harness threw: " . $e->getMessage() . "\n";
    $fail++;
}

echo "\n$pass passed, $fail failed\n";
exit($fail > 0 ? 1 : 0);

<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Knowledge gap analysis — clustering harness.
 *
 * The interesting behaviour of the assistant is emergent: does a pile of real
 * tickets actually collapse into the right questions, and do the one-offs stay
 * out? You cannot answer that by reading the code, only by feeding it a pile
 * whose right answer you already know.
 *
 * Everything runs inside ONE transaction which is ALWAYS rolled back, so this is
 * safe against a live database. It also runs in "wording" mode by default, which
 * needs no OpenAI key and spends nothing — the clustering logic under test is
 * identical, only the similarity function differs.
 *
 *   php tests/knowledge-gaps/run.php
 *
 * ⚠️ Every negative assertion here is paired with a positive control. "The
 * one-offs did not cluster" proves nothing on its own — it is equally true of a
 * harness that silently clustered nothing at all.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/knowledge/gap_analysis.php';

$pass = 0; $fail = 0;

function check(string $what, bool $ok, string $detail = ''): void
{
    global $pass, $fail;
    if ($ok) { $pass++; echo "  [PASS] $what\n"; }
    else     { $fail++; echo "  [FAIL] $what" . ($detail !== '' ? " — $detail" : '') . "\n"; }
}

$conn = connectToDatabase();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (!writeupSchemaReady($conn)) {
    fwrite(STDERR, "The gap tables are missing. Run System → Database Verification first.\n");
    exit(1);
}

$closedStatus = (int)$conn->query("SELECT id FROM ticket_statuses WHERE is_closed = 1 ORDER BY id LIMIT 1")->fetchColumn();
if (!$closedStatus) {
    fwrite(STDERR, "No closed ticket status on this install — cannot build the fixture.\n");
    exit(1);
}

/*
 * The fixture. Five recurring questions phrased the way real people phrase them
 * (different wording, different asset numbers, same question), plus six genuine
 * one-offs that must NOT cluster.
 */
$themes = [
    'vpn' => [
        'VPN keeps disconnecting every few minutes',
        'Re: VPN disconnects constantly when working from home',
        'vpn connection drops repeatedly',
        'VPN disconnecting again - please help',
    ],
    'sharedmailbox' => [
        'Cannot open shared mailbox in Outlook',
        'Outlook will not open the shared mailbox',
        'Unable to open shared mailbox Outlook 365',
        'FW: shared mailbox missing from Outlook',
    ],
    'printerjam' => [
        'Printer on second floor keeps jamming',
        'Second floor printer jamming again',
        'Printer jam - 2nd floor device PR0031',
        'printer keeps jamming second floor',
    ],
    'passwordexpired' => [
        'Password expired cannot log in to laptop',
        'Expired password locked out of laptop',
        'Cannot login laptop password expired',
    ],
    'teamsaudio' => [
        'Teams microphone not working on headset',
        'Microphone not working in Teams headset issue',
        'Teams headset microphone no sound',
    ],
];

$oneOffs = [
    'Order a standing desk for the new starter',
    'Meeting room booking system shows wrong timezone',
    'Please archive the 2019 finance share',
    'Request access to the CAD licence server',
    'Broken window in the server room',
    'Courier delivered a parcel to the wrong floor',
];

echo "Knowledge gap analysis — clustering harness\n";
echo str_repeat('=', 62) . "\n\n";

$conn->beginTransaction();
$inserted = [];

try {
    $ins = $conn->prepare(
        "INSERT INTO tickets (ticket_number, subject, status_id, closed_datetime, created_datetime)
         VALUES (?, ?, ?, ?, ?)"
    );

    $n = 0;
    $mk = function (string $subject) use ($conn, $ins, $closedStatus, &$inserted, &$n) {
        $n++;
        // Spread the closed dates across the last few weeks so the window logic
        // and the first/last-seen columns get exercised on real spans.
        $when = gmdate('Y-m-d H:i:s', time() - ($n * 36 * 3600));
        $ins->execute(['KGTEST-' . $n, $subject, $closedStatus, $when, $when]);
        $id = (int)$conn->lastInsertId();
        $inserted[] = $id;
        return $id;
    };

    $themeIds = [];
    foreach ($themes as $key => $subjects) {
        foreach ($subjects as $s) {
            $themeIds[$key][] = $mk($s);
        }
    }
    $oneOffIds = [];
    foreach ($oneOffs as $s) {
        $oneOffIds[] = $mk($s);
    }

    echo "Fixture: " . count($inserted) . " closed tickets — "
       . count($themes) . " recurring questions + " . count($oneOffs) . " one-offs\n\n";

    /* ---------------------------------------------------------------- *
     * 1. The analysis finds the recurring questions
     * ---------------------------------------------------------------- */
    echo "1. Clustering (wording mode, no API spend)\n";
    $res = gapAnalyse($conn, 1, ['force_wording' => true, 'lookback_days' => 3650]);
    echo "   {$res['message']}\n";
    echo "   analysed={$res['analysed']} gaps={$res['gaps']} clusters={$res['clusters']} mode={$res['mode']}\n";

    $clusters = $conn->query(
        "SELECT c.id, c.label, c.ticket_count, c.status, c.best_ticket_id,
                c.first_ticket_datetime, c.last_ticket_datetime
           FROM knowledge_gap_clusters c ORDER BY c.ticket_count DESC"
    )->fetchAll(PDO::FETCH_ASSOC);

    echo "\n   What the assistant would say:\n";
    foreach ($clusters as $c) {
        echo "     • \"{$c['label']}\" — {$c['ticket_count']} tickets\n";
    }
    echo "\n";

    check('found at least one cluster (positive control — the harness works)', count($clusters) >= 1,
          'found ' . count($clusters));

    // Every theme should appear as a cluster containing its own tickets.
    $memberMap = [];
    foreach ($conn->query("SELECT cluster_id, ticket_id FROM knowledge_gap_cluster_tickets")->fetchAll(PDO::FETCH_ASSOC) as $r) {
        $memberMap[(int)$r['cluster_id']][] = (int)$r['ticket_id'];
    }

    foreach ($themeIds as $key => $ids) {
        $bestHit = 0;
        foreach ($memberMap as $members) {
            $hit = count(array_intersect($ids, $members));
            if ($hit > $bestHit) { $bestHit = $hit; }
        }
        check("theme '$key' clustered ({$bestHit}/" . count($ids) . " of its tickets together)",
              $bestHit >= max(2, count($ids) - 1), "best grouping was $bestHit");
    }

    // Negative control: the one-offs must not be pulled into anything.
    $oneOffClustered = 0;
    foreach ($memberMap as $members) {
        $oneOffClustered += count(array_intersect($oneOffIds, $members));
    }
    check('one-off tickets stayed out of every cluster (negative control)', $oneOffClustered === 0,
          "$oneOffClustered of " . count($oneOffIds) . " were clustered");

    /* ---------------------------------------------------------------- *
     * 2. The richest ticket is the one we would draft from
     * ---------------------------------------------------------------- */
    echo "\n2. Seed selection\n";
    $withBest = 0;
    foreach ($clusters as $c) {
        if (!empty($c['best_ticket_id'])) { $withBest++; }
    }
    check('every cluster names a ticket to draft from', $withBest === count($clusters),
          "$withBest of " . count($clusters));

    $spanOk = true;
    foreach ($clusters as $c) {
        if ($c['first_ticket_datetime'] && $c['last_ticket_datetime']
            && $c['first_ticket_datetime'] > $c['last_ticket_datetime']) {
            $spanOk = false;
        }
    }
    check('first-seen is never after last-seen', $spanOk);

    /* ---------------------------------------------------------------- *
     * 3. A dismissal survives re-analysis — the whole reason clusters are stored
     * ---------------------------------------------------------------- */
    echo "\n3. Dismissal survives a re-run\n";
    if (!$clusters) {
        check('cannot test dismissal without a cluster', false);
    } else {
        $victim = $clusters[0];
        $conn->prepare("UPDATE knowledge_gap_clusters SET status='dismissed', dismissed_by_id=1, dismissed_datetime=UTC_TIMESTAMP() WHERE id=?")
             ->execute([$victim['id']]);

        $res2 = gapAnalyse($conn, 1, ['force_wording' => true, 'lookback_days' => 3650]);

        $still = $conn->prepare("SELECT status FROM knowledge_gap_clusters WHERE id = ?");
        $still->execute([$victim['id']]);
        $status = $still->fetchColumn();

        check("dismissed cluster \"{$victim['label']}\" is still dismissed after re-analysis",
              $status === 'dismissed', 'status is now ' . var_export($status, true));

        // Positive control for the above: a cluster we did NOT dismiss must still
        // be open. Otherwise "still dismissed" could just mean nothing re-ran.
        $openCount = (int)$conn->query("SELECT COUNT(*) FROM knowledge_gap_clusters WHERE status='open'")->fetchColumn();
        check('the clusters we did not dismiss are still open (positive control)',
              $openCount >= 1, "open clusters: $openCount");
        check('re-analysis did not duplicate clusters', $res2['clusters'] === $res['clusters'],
              "first run {$res['clusters']}, second run {$res2['clusters']}");
    }

    /* ---------------------------------------------------------------- *
     * 4. An article that covers a question makes the gap disappear
     * ---------------------------------------------------------------- */
    echo "\n4. Writing the article closes the gap\n";
    $before = (int)$conn->query("SELECT COUNT(*) FROM knowledge_gap_clusters WHERE status='open'")->fetchColumn();

    $conn->prepare(
        "INSERT INTO knowledge_articles (title, body, author_id, is_published, is_archived)
         VALUES (?, ?, 1, 1, 0)"
    )->execute(['VPN keeps disconnecting', '<p>How to fix a VPN that disconnects repeatedly.</p>']);

    $res3  = gapAnalyse($conn, 1, ['force_wording' => true, 'lookback_days' => 3650]);
    $after = (int)$conn->query("SELECT COUNT(*) FROM knowledge_gap_clusters WHERE status='open'")->fetchColumn();

    echo "   open clusters before the article: $before, after: $after\n";
    check('publishing a matching article removed a gap', $after < $before,
          "before $before, after $after");

} catch (Throwable $e) {
    echo "\n  [ERROR] " . $e->getMessage() . "\n";
    echo "  " . $e->getFile() . ':' . $e->getLine() . "\n";
    $fail++;
} finally {
    $conn->rollBack();
}

/* -------------------------------------------------------------------- *
 * 5. Rollback actually rolled back. Without this the whole harness is a
 *    liability rather than a test — it writes to a live database.
 * -------------------------------------------------------------------- */
echo "\n5. Cleanup\n";
$leftTickets = 0;
if ($inserted) {
    $in = implode(',', array_fill(0, count($inserted), '?'));
    $st = $conn->prepare("SELECT COUNT(*) FROM tickets WHERE id IN ($in)");
    $st->execute($inserted);
    $leftTickets = (int)$st->fetchColumn();
}
check('every fixture ticket was rolled back', $leftTickets === 0, "$leftTickets still present");

$leftTest = (int)$conn->query("SELECT COUNT(*) FROM tickets WHERE ticket_number LIKE 'KGTEST-%'")->fetchColumn();
check('no KGTEST tickets left anywhere', $leftTest === 0, "$leftTest found");

$leftArticle = (int)$conn->query("SELECT COUNT(*) FROM knowledge_articles WHERE title = 'VPN keeps disconnecting'")->fetchColumn();
check('the fixture article was rolled back', $leftArticle === 0, "$leftArticle found");

echo "\n" . str_repeat('=', 62) . "\n";
echo ($fail === 0 ? "ALL GREEN" : "FAILURES") . " — $pass passed, $fail failed\n";
exit($fail === 0 ? 0 : 1);

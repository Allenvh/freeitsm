<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Ticket numbering (GH discussion #71).
 *
 * The assertions that matter are not "it makes a number". They are:
 *
 *   1. A number is NEVER issued twice — including one a renumbered ticket used
 *      to have, because a reply quoting it must not land on a stranger's ticket.
 *   2. An old number KEEPS RESOLVING forever, because the emails quoting it live
 *      in customers' inboxes.
 *   3. The reference parser recognises ANY format, because an install can change
 *      format and every number it ever issued has to go on working.
 *   4. The padding is a MINIMUM, never a limit.
 *
 * ⚠️ Creates only `ZZNUM`-prefixed rows and sweeps them before AND after.
 *
 * Run:  php tests/ticket-numbering.php
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/ticket_numbering.php';

$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}

$conn = connectToDatabase();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sweep = function () use ($conn): void {
    $conn->query("DELETE FROM ticket_number_history WHERE ticket_number LIKE 'ZZNUM%'");
    // 🔴 NEVER 't'. That is the counter every ticket on the install draws from,
    // and a test that deletes it leaves the estate numbered above a counter that
    // has gone back to the start — which stops mail collection dead. Both keys
    // here name entities that cannot exist.
    $conn->query("DELETE FROM ticket_number_counters WHERE counter_key IN ('t:ty999999','t:co999999')");
    // ⚠️ The renumber tests make REAL ticket rows. Sweep them by their number
    // AND their history, in that order, or a failed run leaves orphans behind
    // that the next run then tries to renumber.
    $conn->query("DELETE h FROM ticket_number_history h JOIN tickets t ON t.id = h.ticket_id WHERE t.subject LIKE 'ZZNUM renumber test%'");
    $conn->query("DELETE FROM tickets WHERE subject LIKE 'ZZNUM renumber test%'");
};
$sweep();
TicketNumbering::forget();

$countBefore = (int)$conn->query("SELECT COUNT(*) FROM tickets")->fetchColumn();

// 🔴 THE ASSERTION THAT MATTERS MOST IN THIS FILE, and it is about the test
// suite rather than the product. These tests write real counter rows. If one
// ever writes the PRODUCTION key, it silently breaks the install it was run on
// — that is exactly what happened, and it took mail collection down.
//
// So the live counter is photographed before the run and compared after.
$liveCountersBefore = [];
foreach ($conn->query("SELECT counter_key, next_value FROM ticket_number_counters") as $row) {
    if (!str_contains($row['counter_key'], '999999')) {
        $liveCountersBefore[$row['counter_key']] = (int)$row['next_value'];
    }
}

try {
    // ================================================================
    echo "\n--- the format engine ---\n";
    // ================================================================

    ok('pads to the width given',
        TicketNumbering::render('ZZNUM-{######}', 42) === 'ZZNUM-000042',
        TicketNumbering::render('ZZNUM-{######}', 42));

    // 🔑 The point Ed raised: a big organisation reaches a million sooner than
    // you would think, and the format must not become a cliff.
    ok('a million tickets still fits',
        TicketNumbering::render('ZZNUM-{######}', 1000000) === 'ZZNUM-1000000',
        TicketNumbering::render('ZZNUM-{######}', 1000000));
    ok('...and TEN million just gets longer, nothing breaks',
        TicketNumbering::render('ZZNUM-{######}', 10000000) === 'ZZNUM-10000000',
        TicketNumbering::render('ZZNUM-{######}', 10000000));

    $y = (new DateTimeImmutable('now', new DateTimeZone('UTC')))->format('Y');
    ok('year token', TicketNumbering::render('ZZNUM-{YYYY}-{####}', 7) === "ZZNUM-{$y}-0007",
        TicketNumbering::render('ZZNUM-{YYYY}-{####}', 7));
    ok('any width works', TicketNumbering::render('{###}', 5) === '005');

    echo "\n--- format validation ---\n";
    ok('a format with no number token is refused',
        count(TicketNumbering::validateFormat('ZZNUM-ONLY')) === 1);
    ok('square brackets are refused (they would break the email tag)',
        (bool)array_filter(TicketNumbering::validateFormat('[{####}]'), fn($p) => str_contains($p, 'brackets')));
    ok('spaces are refused',
        (bool)array_filter(TicketNumbering::validateFormat('ZZ NUM-{####}'), fn($p) => str_contains($p, 'Spaces')));
    ok('a format longer than the column is refused',
        (bool)array_filter(TicketNumbering::validateFormat(str_repeat('X', 60) . '-{####}'), fn($p) => str_contains($p, '50')));
    ok('a good format passes', TicketNumbering::validateFormat('ZZNUM-{######}') === []);

    // ================================================================
    echo "\n--- the counter ---\n";
    // ================================================================

    TicketNumbering::withSettings([
        'ticket_number_style'  => 'sequential',
        'ticket_number_format' => 'ZZNUM-{#####}',
        'ticket_number_start'  => '1',
        'ticket_number_scope'  => 'per_type',
        'ticket_number_reset'  => 'never',
    ]);

    // ⚠️ A THROWAWAY COUNTER. With scope 'global' the key is literally 't' —
    // the one a real install uses — so an earlier version of this test was
    // winding the production counter forward. Scoping to a type id nothing owns
    // gives the test its own sequence to play with.
    $TESTTYPE = 999999;

    $cfg = ['ticket_number_scope' => 'global', 'ticket_number_reset' => 'never'];
    ok('the counter key is stable', TicketNumbering::counterKey($cfg, 5, 2) === 't');

    $perType = TicketNumbering::counterKey(['ticket_number_scope' => 'per_type'], 5, null);
    ok('per-type gives each type its own counter', $perType === 't:ty5', $perType);

    $yearly = TicketNumbering::counterKey(['ticket_number_reset' => 'yearly'], null, null);
    ok('a yearly reset is just a different counter, not a job', $yearly === "t:{$y}", $yearly);

    echo "\n--- issuing numbers ---\n";

    $n1 = TicketNumbering::next($conn, $TESTTYPE, null);
    $n2 = TicketNumbering::next($conn, $TESTTYPE, null);
    $n3 = TicketNumbering::next($conn, $TESTTYPE, null);
    ok('the first number is the configured start', $n1 === 'ZZNUM-00001', $n1);
    ok('numbers increment', $n2 === 'ZZNUM-00002' && $n3 === 'ZZNUM-00003', "{$n2} {$n3}");
    ok('...and they are all different', count(array_unique([$n1, $n2, $n3])) === 3);

    // ================================================================
    echo "\n--- 🔑 A RETIRED NUMBER IS NEVER REISSUED ---\n";
    // ================================================================

    // Park ZZNUM-00099 in history against a real ticket, then prove the
    // generator refuses to hand it out again.
    $someTicket = (int)$conn->query("SELECT id FROM tickets ORDER BY id LIMIT 1")->fetchColumn();
    if ($someTicket) {
        $conn->prepare("INSERT INTO ticket_number_history (ticket_id, ticket_number, reason) VALUES (?, 'ZZNUM-00099', 'renumber')")
             ->execute([$someTicket]);

        ok('a number held only in HISTORY counts as in use',
            TicketNumbering::inUse($conn, 'ZZNUM-00099'));

        // 🔑 The whole point: an old number still finds its ticket.
        ok('an old number still resolves to its ticket',
            TicketNumbering::findTicketId($conn, 'ZZNUM-00099') === $someTicket);

        // Wind the counter to 98 so the next number WOULD be 99 without the guard.
        $conn->prepare("UPDATE ticket_number_counters SET next_value = 98 WHERE counter_key = 't:ty999999'")->execute();
        $next = TicketNumbering::next($conn, $TESTTYPE, null);
        ok('the generator SKIPS a retired number rather than reusing it',
            $next !== 'ZZNUM-00099', $next);
    } else {
        ok('(skipped — no tickets on this install)', true);
    }

    // ================================================================
    echo "\n--- 🔴 THE PARSER MUST NOT ENCODE A FORMAT ---\n";
    // ================================================================

    // Every one of these has been, or could be, a real ticket number on some
    // install. The parser has to find all of them.
    $shapes = [
        'CKQ-418-73926'      => "today's random format",
        'TICKET-000042'      => 'a padded sequential',
        'INC-2026-0007'      => 'a prefix with a year',
        'ABC-123-XYZ'        => 'a number migrated from another system',
        '1010138'            => 'a bare increment, Zammad style',
        'SD/2026/000123'     => 'slashes',
    ];
    $found = 0;
    foreach ($shapes as $num => $desc) {
        // ⚠️ (string) is load-bearing: PHP silently casts a numeric-string array
        // KEY to an integer, so '1010138' arrives here as int 1010138 and a
        // === against the captured string would fail for the format that is
        // most likely to be used by an install migrating from Zammad.
        $num     = (string)$num;
        $subject = "Re: [SDREF:{$num}] Printer jammed";
        if (preg_match(TicketNumbering::REF_PATTERN, $subject, $m) && $m[1] === $num) {
            $found++;
        } else {
            echo "        (missed {$desc}: {$num})\n";
        }
    }
    ok('the parser recognises EVERY format, old and new', $found === count($shapes),
        "{$found}/" . count($shapes));

    ok('the reply-above-this-line marker is format-agnostic too',
        preg_match(TicketNumbering::REF_LINE_PATTERN,
                   '[*** SDREF:TICKET-000042 REPLY ABOVE THIS LINE ***]', $m2) && $m2[1] === 'TICKET-000042');

    ok('a malformed subject cannot swallow the line',
        !preg_match(TicketNumbering::REF_PATTERN, '[SDREF:has spaces in it] hello'));

    // ================================================================
    echo "\n--- preview writes nothing ---\n";
    // ================================================================

    $before = (int)$conn->query("SELECT next_value FROM ticket_number_counters WHERE counter_key='t:ty999999'")->fetchColumn();
    $prev = TicketNumbering::preview([
        'ticket_number_style'  => 'sequential',
        'ticket_number_format' => 'ZZNUM-{####}',
        'ticket_number_start'  => '500',
    ], 3);
    ok('preview shows what would come next', $prev === ['ZZNUM-0500', 'ZZNUM-0501', 'ZZNUM-0502'],
        json_encode($prev));
    $after = (int)$conn->query("SELECT next_value FROM ticket_number_counters WHERE counter_key='t:ty999999'")->fetchColumn();
    ok('...and moves no counter', $before === $after, "{$before} -> {$after}");

    // ================================================================
    echo "\n--- the company code behind {COMPANY} ---\n";
    // ================================================================

    ok('a code that was typed wins',
        TicketNumbering::codeFor(['name' => 'Acme Ltd', 'slug' => 'acme-limited', 'ticket_code' => 'ACM1']) === 'ACM1');
    ok('...then the slug',
        TicketNumbering::codeFor(['name' => 'Acme Ltd', 'slug' => 'acme-limited', 'ticket_code' => null]) === 'ACMELIMITED');
    ok('...then the name, three letters',
        TicketNumbering::codeFor(['name' => 'Acme Ltd', 'slug' => null, 'ticket_code' => null]) === 'ACM');
    ok('an empty code is not a code',
        TicketNumbering::codeFor(['name' => 'Acme Ltd', 'slug' => null, 'ticket_code' => '   ']) === 'ACM');
    ok('a name with no letters gives nothing rather than nonsense',
        TicketNumbering::codeFor(['name' => '123 456', 'slug' => null, 'ticket_code' => null]) === '');

    ok('a code is upper-cased and stripped', TicketNumbering::cleanCode('ed-moz 99!') === 'EDMOZ99',
        TicketNumbering::cleanCode('ed-moz 99!'));
    ok('a code is capped at twelve', TicketNumbering::cleanCode(str_repeat('A', 30)) === str_repeat('A', 12));

    // 🔴 THE WHOLE POINT. Two companies sharing a code means two companies
    // producing the same ticket numbers under per-company counting, and
    // ticket_number is unique across the entire install.
    $clashRows = [
        ['name' => 'Acme Ltd',    'slug' => null, 'ticket_code' => null],
        ['name' => 'Acme Group',  'slug' => null, 'ticket_code' => null],
        ['name' => 'Beta Ltd',    'slug' => null, 'ticket_code' => null],
    ];
    $codes = array_map(fn($r) => TicketNumbering::codeFor($r), $clashRows);
    ok('two similarly-named companies DO derive the same code',
        $codes[0] === $codes[1] && $codes[0] !== $codes[2], json_encode($codes));
    ok('...and an explicit code separates them',
        TicketNumbering::codeFor(['name' => 'Acme Group', 'slug' => null, 'ticket_code' => 'ACG']) !== $codes[0]);

    // codeClashes() against the real table: it must agree with itself, whatever
    // this install happens to contain.
    $clashes = TicketNumbering::codeClashes($conn);
    $seen = [];
    $dupFound = false;
    foreach ($conn->query("SELECT id, name, slug, ticket_code FROM tenants WHERE is_active = 1") as $t) {
        $c = TicketNumbering::codeFor($t);
        if (isset($seen[$c])) $dupFound = true;
        $seen[$c] = true;
    }
    ok('codeClashes agrees with the codes it derives',
        $dupFound === (count($clashes) > 0) || array_key_exists('', $clashes),
        json_encode(array_keys($clashes)));

    // 🔑 A format carrying {COMPANY} renders the company's code, and a company
    // that does not exist renders nothing rather than an id.
    $realTenant = (int)$conn->query("SELECT id FROM tenants ORDER BY id LIMIT 1")->fetchColumn();
    $realCode   = TicketNumbering::codeFor(
        $conn->query("SELECT name, slug, ticket_code FROM tenants WHERE id = {$realTenant}")->fetch(PDO::FETCH_ASSOC)
    );
    ok('{COMPANY} renders the code',
        TicketNumbering::render('ZZNUM{COMPANY}-{####}', 1, $conn, null, null, $realTenant) === "ZZNUM{$realCode}-0001",
        TicketNumbering::render('ZZNUM{COMPANY}-{####}', 1, $conn, null, null, $realTenant));
    ok('a company that is not there renders nothing, not an id',
        TicketNumbering::render('ZZNUM{COMPANY}-{####}', 1, $conn, null, null, 999999) === 'ZZNUM-0001');

    // ================================================================
    echo "\n--- planning a renumber (writes nothing) ---\n";
    // ================================================================
    //
    // ⚠️ planRenumber() normally reads EVERY ticket. These tests hand it a set
    // of their own instead, so a bug here cannot rewrite the real estate.

    $mk = function (int $id, string $num, ?int $type, ?int $tenant, string $when): array {
        return ['id' => $id, 'ticket_number' => $num, 'ticket_type_id' => $type,
                'tenant_id' => $tenant, 'created_datetime' => $when];
    };
    $fakeRows = [
        $mk(9001, 'AAA-111-11111', null, null, '2024-03-04 09:00:00'),
        $mk(9002, 'BBB-222-22222', null, null, '2024-11-20 09:00:00'),
        $mk(9003, 'CCC-333-33333', null, null, '2025-01-06 09:00:00'),
    ];
    $seqCfg = ['ticket_number_style' => 'sequential', 'ticket_number_format' => 'ZZNUM-{#####}'];

    $plan = TicketNumbering::planRenumber($conn, $seqCfg, $fakeRows);
    ok('plans every ticket', $plan['total'] === 3 && $plan['changing'] === 3 && $plan['skipped'] === 0,
        json_encode([$plan['total'], $plan['changing'], $plan['skipped']]));
    ok('numbers run oldest first',
        array_column($plan['planned'], 'to') === ['ZZNUM-00001', 'ZZNUM-00002', 'ZZNUM-00003'],
        json_encode(array_column($plan['planned'], 'to')));
    ok('keeps the old number so history can be written',
        $plan['planned'][0]['from'] === 'AAA-111-11111');
    ok('says what the next new ticket would get', $plan['next_after'] === 'ZZNUM-00004',
        (string)$plan['next_after']);

    // 🔑 The whole point of $at: a 2024 ticket must not come back stamped 2026.
    $plan = TicketNumbering::planRenumber($conn,
        ['ticket_number_style' => 'sequential', 'ticket_number_format' => 'ZZNUM-{YYYY}-{####}'],
        $fakeRows);
    ok('a ticket keeps its OWN year, not today\'s',
        array_column($plan['planned'], 'to') === ['ZZNUM-2024-0001', 'ZZNUM-2024-0002', 'ZZNUM-2025-0003'],
        json_encode(array_column($plan['planned'], 'to')));

    // ...and with a yearly reset each year is its own counter, so numbering
    // starts again — which is the only reason to carry the year at all.
    $plan = TicketNumbering::planRenumber($conn,
        ['ticket_number_style' => 'sequential', 'ticket_number_format' => 'ZZNUM-{YYYY}-{####}',
         'ticket_number_reset' => 'yearly'],
        $fakeRows);
    ok('a yearly reset starts each year again',
        array_column($plan['planned'], 'to') === ['ZZNUM-2024-0001', 'ZZNUM-2024-0002', 'ZZNUM-2025-0001'],
        json_encode(array_column($plan['planned'], 'to')));

    $plan = TicketNumbering::planRenumber($conn, $seqCfg,
        [$mk(9001, 'ZZNUM-00001', null, null, '2024-03-04 09:00:00'),
         $mk(9002, 'BBB-222-22222', null, null, '2024-11-20 09:00:00')]);
    ok('a ticket already in the scheme is left alone',
        $plan['skipped'] === 1 && $plan['changing'] === 1
        && $plan['planned'][0]['to'] === 'ZZNUM-00002',
        json_encode($plan['planned']));

    echo "\n--- planning REFUSES what would break references ---\n";

    // 🔴 The one outcome that must never reach the database: two tickets the
    // same number. Counting per type without {TYPE} in the format does exactly
    // that, and it is not obvious from the settings screen.
    $threw = false;
    try {
        TicketNumbering::planRenumber($conn,
            ['ticket_number_style' => 'sequential', 'ticket_number_format' => 'ZZNUM-{#####}',
             'ticket_number_scope' => 'per_type'],
            [$mk(9001, 'A', 1, null, '2024-03-04 09:00:00'), $mk(9002, 'B', 2, null, '2024-03-05 09:00:00')]);
    } catch (Exception $e) { $threw = str_contains($e->getMessage(), '{TYPE}'); }
    ok('per-type counting without {TYPE} is refused', $threw);

    $threw = false;
    try {
        TicketNumbering::planRenumber($conn,
            ['ticket_number_style' => 'sequential', 'ticket_number_format' => 'ZZNUM-{#####}',
             'ticket_number_scope' => 'per_company'],
            [$mk(9001, 'A', null, 1, '2024-03-04 09:00:00')]);
    } catch (Exception $e) { $threw = str_contains($e->getMessage(), '{COMPANY}'); }
    ok('per-company counting without {COMPANY} is refused', $threw);

    // 🔑 And the guard that does not rely on me having thought of the cause:
    // {TYPE} IS in the format, but both types were deleted years ago so it
    // renders empty and the numbers collide anyway.
    $threw = false; $msg = '';
    try {
        TicketNumbering::planRenumber($conn,
            ['ticket_number_style' => 'sequential', 'ticket_number_format' => 'ZZNUM{TYPE}-{#####}',
             'ticket_number_scope' => 'per_type'],
            [$mk(9001, 'A', 888881, null, '2024-03-04 09:00:00'),
             $mk(9002, 'B', 888882, null, '2024-03-05 09:00:00')]);
    } catch (Exception $e) { $threw = true; $msg = $e->getMessage(); }
    ok('a duplicate number is caught however it arose', $threw
        && str_contains($msg, 'more than one ticket'), $msg);

    ok('the random style cannot be renumbered into',
        (function () use ($conn, $fakeRows) {
            try { TicketNumbering::planRenumber($conn, ['ticket_number_style' => 'random'], $fakeRows); }
            catch (Exception $e) { return str_contains($e->getMessage(), 'sequential'); }
            return false;
        })());

    // ================================================================
    echo "\n--- carrying a renumber out, for real ---\n";
    // ================================================================
    //
    // ⚠️ Real rows this time, but ONLY ones this test made. applyRenumber()
    // touches nothing outside the plan it is given.

    $ins = $conn->prepare(
        "INSERT INTO tickets (ticket_number, subject, created_datetime) VALUES (?, ?, ?)"
    );
    $ins->execute(['ZZNUM-OLD-A', 'ZZNUM renumber test A', '2024-03-04 09:00:00']);
    $idA = (int)$conn->lastInsertId();
    $ins->execute(['ZZNUM-OLD-B', 'ZZNUM renumber test B', '2024-11-20 09:00:00']);
    $idB = (int)$conn->lastInsertId();

    // ⚠️ THE WRITING TESTS MUST NEVER TOUCH A REAL COUNTER KEY.
    //
    // 🔴 This bit me for real. The renumber tests below call applyRenumber(),
    // which winds a counter — and under the default `global` scope the counter
    // key is literally 't', the one every ticket on the install draws from. A
    // test run reset Ed's live counter to a test value while 106 renumbered
    // tickets sat above it, and mail collection stopped: every new number
    // collided with one that already existed.
    //
    // So the writing tests run under `per_company` with a company that cannot
    // exist, giving the key 't:co999999'. {COMPANY} renders empty for it, so the
    // expected numbers are unchanged and only the KEY differs. The read-only
    // planning tests keep the global scope, because planning writes nothing.
    $applyCfg = [
        'ticket_number_style'  => 'sequential',
        'ticket_number_format' => 'ZZNUM{COMPANY}-{#####}',
        'ticket_number_scope'  => 'per_company',
    ];
    $FAKECO = 999999;
    $realRows = [
        $mk($idA, "ZZNUM-OLD-A", null, $FAKECO, "2024-03-04 09:00:00"),
        $mk($idB, "ZZNUM-OLD-B", null, $FAKECO, "2024-11-20 09:00:00"),
    ];
    $plan = TicketNumbering::planRenumber($conn, $applyCfg, $realRows);
    TicketNumbering::applyRenumber($conn, $plan);

    $numA = $conn->query("SELECT ticket_number FROM tickets WHERE id = {$idA}")->fetchColumn();
    $numB = $conn->query("SELECT ticket_number FROM tickets WHERE id = {$idB}")->fetchColumn();
    ok('the tickets carry their new numbers', $numA === 'ZZNUM-00001' && $numB === 'ZZNUM-00002',
        "{$numA} / {$numB}");

    // 🔑 THE ASSERTION THE WHOLE FEATURE RESTS ON. An email quoting the old
    // number is still delivered to the right ticket, for ever.
    ok('the OLD number still finds the ticket',
        TicketNumbering::findTicketId($conn, 'ZZNUM-OLD-A') === $idA);
    ok('...and the new one does too',
        TicketNumbering::findTicketId($conn, 'ZZNUM-00001') === $idA);
    ok('a retired number counts as in use, so nobody else gets it',
        TicketNumbering::inUse($conn, 'ZZNUM-OLD-B'));

    // 🔑 And the counter was wound past what the run issued — otherwise the
    // next new ticket would collide with a renumbered one.
    $counter = (int)$conn->query("SELECT next_value FROM ticket_number_counters WHERE counter_key = 't:co999999'")->fetchColumn();
    ok('the counter was wound past the run', $counter === 3, (string)$counter);

    // Renumbering the same tickets again into the same scheme is a no-op —
    // they already match, so no second history row is written.
    $again = TicketNumbering::planRenumber($conn, $applyCfg,
        [$mk($idA, "ZZNUM-00001", null, $FAKECO, "2024-03-04 09:00:00"),
         $mk($idB, "ZZNUM-00002", null, $FAKECO, "2024-11-20 09:00:00")]);
    ok('running it twice changes nothing the second time',
        $again['changing'] === 0 && $again['skipped'] === 2);

    $hist = (int)$conn->query(
        "SELECT COUNT(*) FROM ticket_number_history WHERE ticket_id IN ({$idA},{$idB})"
    )->fetchColumn();
    ok('exactly one history row per renumbered ticket', $hist === 2, (string)$hist);


    // 🔴 THE BRANCH THAT PROTECTS OLD EMAIL THREADS. Renumber both again from 5,
    // so ZZNUM-00001 becomes a number ticket A has RETIRED. Then ask for ticket
    // B alone to be numbered from 1: it wants ZZNUM-00001, which would silently
    // redirect every reply quoting A's old number onto B.
    $second = TicketNumbering::planRenumber($conn,
        $applyCfg + ["ticket_number_start" => "5"],
        [$mk($idA, "ZZNUM-00001", null, $FAKECO, "2024-03-04 09:00:00"),
         $mk($idB, "ZZNUM-00002", null, $FAKECO, "2024-11-20 09:00:00")]);
    TicketNumbering::applyRenumber($conn, $second);
    ok('a second renumber moves them on again',
        $conn->query("SELECT ticket_number FROM tickets WHERE id = {$idA}")->fetchColumn() === 'ZZNUM-00005');
    ok('and BOTH old numbers still find the ticket',
        TicketNumbering::findTicketId($conn, 'ZZNUM-OLD-A') === $idA
        && TicketNumbering::findTicketId($conn, 'ZZNUM-00001') === $idA);

    $threw = false; $msg = '';
    try {
        TicketNumbering::planRenumber($conn, $seqCfg,
            [$mk($idB, 'ZZNUM-00006', null, null, '2024-11-20 09:00:00')]);
    } catch (Exception $e) { $threw = true; $msg = $e->getMessage(); }
    ok('a number ANOTHER ticket retired is refused',
        $threw && str_contains($msg, 'used by another ticket'), $msg);

    // ...but a ticket taking back its OWN retired number is fine — that is
    // simply a renumber being undone, and nothing is redirected anywhere.
    $undo = TicketNumbering::planRenumber($conn,
        ['ticket_number_style' => 'sequential', 'ticket_number_format' => 'ZZNUM-{#####}',
         'ticket_number_start' => '2'],
        [$mk($idB, 'ZZNUM-00006', null, null, '2024-11-20 09:00:00')]);
    ok('a ticket may take back its own retired number',
        $undo['planned'][0]['to'] === 'ZZNUM-00002', json_encode($undo['planned']));

    $conn->exec("DELETE FROM ticket_number_history WHERE ticket_id IN ({$idA},{$idB})");
    $conn->exec("DELETE FROM tickets WHERE id IN ({$idA},{$idB})");

    echo "\n--- random style still works (the default) ---\n";
    TicketNumbering::withSettings(['ticket_number_style' => 'random']);
    $r = TicketNumbering::next($conn, $TESTTYPE, null);
    ok('random keeps the historical shape', (bool)preg_match('/^[A-Z]{3}-\d{3}-\d{5}$/', $r), $r);

} catch (Throwable $e) {
    $fail++;
    echo "\n  FATAL  " . get_class($e) . ': ' . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}

TicketNumbering::withSettings(null);
$sweep();

echo "\n";
$countAfter = (int)$conn->query("SELECT COUNT(*) FROM tickets")->fetchColumn();
ok('no tickets were created or destroyed', $countAfter === $countBefore, "{$countBefore} -> {$countAfter}");

$liveCountersAfter = [];
foreach ($conn->query("SELECT counter_key, next_value FROM ticket_number_counters") as $row) {
    if (!str_contains($row['counter_key'], '999999')) {
        $liveCountersAfter[$row['counter_key']] = (int)$row['next_value'];
    }
}
ok('🔴 NO REAL COUNTER WAS TOUCHED', $liveCountersAfter === $liveCountersBefore,
    json_encode(['before' => $liveCountersBefore, 'after' => $liveCountersAfter]));

echo "\n" . str_repeat('=', 52) . "\n";
echo ($fail === 0 ? 'ALL GREEN' : 'FAILURES') . ": {$pass} passed, {$fail} failed\n";
exit($fail === 0 ? 0 : 1);

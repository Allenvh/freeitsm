<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Saved table views (discussion #96).
 *
 * The point of this file is the VISIBILITY rules. There are three answers
 * (mine, my team's, everyone's) and a fourth that must never happen — somebody
 * else's private view — so each is checked from the side that must be refused
 * as well as the side that must work.
 *
 * ⚠️ Touches the database. Everything it makes is named ZZTV and removed in the
 * cleanup at the bottom, including on failure.
 *
 * Run:  php tests/table-views.php
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/table_views.php';

$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}
function refuses(string $what, callable $fn): void {
    try { $fn(); ok($what, false, 'it did NOT refuse'); }
    catch (Throwable $e) { ok($what, true); }
}
/** Is $viewId in the list $analystId can see? */
function sees(PDO $c, int $analystId, string $key, int $viewId): bool {
    foreach (tableViewList($c, $analystId, $key) as $v) {
        if ((int)$v['id'] === $viewId) return true;
    }
    return false;
}

echo "\nSaved table views (#96)\n" . str_repeat('=', 70) . "\n";

$conn = connectToDatabase();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Real people with real, differing team membership. Picked rather than created,
// because the whole feature turns on analyst_teams being what it says it is.
$teamsOf = fn(int $id) => tableViewTeamIds($conn, $id);
$owner   = 1;                                   // in two teams
$teams   = $teamsOf($owner);
if (count($teams) < 2) { echo "  SKIP: analyst 1 needs to be in two teams for this to prove anything\n"; exit(0); }
[$teamA, $teamB] = $teams;

$inA = null; $inB = null; $inNone = null;
foreach ($conn->query("SELECT id FROM analysts WHERE id <> 1 AND is_active = 1")->fetchAll(PDO::FETCH_COLUMN) as $id) {
    $t = $teamsOf((int)$id);
    if ($inA === null    && in_array($teamA, $t, true) && !in_array($teamB, $t, true)) $inA = (int)$id;
    if ($inB === null    && in_array($teamB, $t, true) && !in_array($teamA, $t, true)) $inB = (int)$id;
    if ($inNone === null && !$t) $inNone = (int)$id;
}
printf("  owner=%d  teamA=%d (member %s)  teamB=%d (member %s)  no-teams member=%s\n\n",
    $owner, $teamA, $inA ?? '-', $teamB, $inB ?? '-', $inNone ?? '-');

$made = [];
$mk = function (string $name, string $vis, ?int $teamId, string $key = 'assets') use ($conn, $owner, &$made): int {
    $id = tableViewSave($conn, $owner, [
        'table_key'  => $key,
        'name'       => 'ZZTV ' . $name,
        'description'=> 'ZZTV description for ' . $name,
        'visibility' => $vis,
        'team_id'    => $teamId,
        'config'     => ['cols' => [['k' => 'hostname', 'v' => 1]], 'sort' => ['k' => 'hostname', 'd' => 'asc']],
    ]);
    $made[] = $id;
    return $id;
};

try {
    // ---- 1. Creating -------------------------------------------------------
    echo "Creating:\n";
    $priv = $mk('private one', 'private', null);
    ok('a private view is created', $priv > 0);
    ok('the owner can see their own', sees($conn, $owner, 'assets', $priv));
    ok('and may edit it', tableViewLoad($conn, $owner, $priv)['can_edit'] === true);

    $pub  = $mk('public one', 'public', null);
    $tvA  = $mk('team A one', 'team', $teamA);
    $tvB  = $mk('team B one', 'team', $teamB);

    // ---- 2. Who can see what ----------------------------------------------
    echo "\nVisibility - the point of this file:\n";
    if ($inA !== null) {
        ok('somebody else CANNOT see a private view', !sees($conn, $inA, 'assets', $priv));
        ok('somebody else CAN see a public view',      sees($conn, $inA, 'assets', $pub));
        ok('a team member sees their team\'s view',    sees($conn, $inA, 'assets', $tvA));
        ok('and NOT the other team\'s view',          !sees($conn, $inA, 'assets', $tvB),
           'a view shared with a team they are not in was visible');
    }
    if ($inB !== null) {
        ok('the other team sees theirs',               sees($conn, $inB, 'assets', $tvB));
        ok('and not the first team\'s',               !sees($conn, $inB, 'assets', $tvA));
    }
    if ($inNone !== null) {
        // The clause is built from the reader's teams. With none, it has to fall
        // back to owner-or-public rather than producing broken SQL.
        ok('an analyst in NO teams still sees public views', sees($conn, $inNone, 'assets', $pub));
        ok('an analyst in no teams sees no team views',     !sees($conn, $inNone, 'assets', $tvA));
        ok('and still cannot see a private one',            !sees($conn, $inNone, 'assets', $priv));
    }

    // ---- 3. Scoped to their own table -------------------------------------
    echo "\nScoping to one table:\n";
    $taskView = $mk('a tasks view', 'public', null, 'tasks');
    ok('a tasks view appears on the tasks table',  sees($conn, $owner, 'tasks',  $taskView));
    ok('and NOT on the asset table',              !sees($conn, $owner, 'assets', $taskView),
       'the table_key condition was lost - check the visibility clause is bracketed');
    ok('an asset view does not appear on tasks',  !sees($conn, $owner, 'tasks',  $pub));

    // ---- 4. Who can change what -------------------------------------------
    echo "\nWriting (each of these must refuse):\n";
    if ($inA !== null) {
        refuses('somebody else cannot update a view they can SEE',
            fn() => tableViewSave($conn, $inA, ['id' => $pub, 'table_key' => 'assets', 'name' => 'hijacked',
                                                'visibility' => 'public', 'config' => ['x' => 1]]));
        refuses('somebody else cannot delete it either',
            fn() => tableViewDelete($conn, $inA, $pub));
        ok('and can_edit says so before they try',
            tableViewLoad($conn, $inA, $pub)['can_edit'] === false);
    }
    refuses('a view cannot be shared with a team you are not in', function () use ($conn, $inNone, $teamA, $owner) {
        // Somebody with no teams at all trying to share into one.
        tableViewSave($conn, $inNone ?? $owner, ['table_key' => 'assets', 'name' => 'ZZTV sneaky',
            'visibility' => 'team', 'team_id' => $teamA === 0 ? 1 : ($inNone !== null ? $teamA : 999999),
            'config' => ['x' => 1]]);
    });
    refuses('an unknown table is refused',
        fn() => tableViewSave($conn, $owner, ['table_key' => 'nonsense', 'name' => 'ZZTV x',
                                              'visibility' => 'private', 'config' => ['x' => 1]]));
    refuses('a nameless view is refused',
        fn() => tableViewSave($conn, $owner, ['table_key' => 'assets', 'name' => '   ',
                                              'visibility' => 'private', 'config' => ['x' => 1]]));
    refuses('an unreadable config is refused',
        fn() => tableViewSave($conn, $owner, ['table_key' => 'assets', 'name' => 'ZZTV bad config',
                                              'visibility' => 'private', 'config' => '{not json']));
    ok('POSITIVE CONTROL: the owner can still update their own', (function () use ($conn, $owner, $priv) {
        tableViewSave($conn, $owner, ['id' => $priv, 'table_key' => 'assets', 'name' => 'ZZTV renamed',
                                      'visibility' => 'private', 'config' => ['x' => 1]]);
        return tableViewLoad($conn, $owner, $priv)['name'] === 'ZZTV renamed';
    })());

    // ---- 5. Search ---------------------------------------------------------
    echo "\nSearch:\n";
    ok('by name',        count(tableViewList($conn, $owner, 'assets', 'public one')) === 1);
    ok('by description', count(tableViewList($conn, $owner, 'assets', 'description for team A')) === 1);
    ok('by owner name',  count(tableViewList($conn, $owner, 'assets', 'Administrator')) >= 4);
    ok('a search matching nothing returns nothing',
       tableViewList($conn, $owner, 'assets', 'ZZTV-no-such-view') === []);

    // ---- 6. Last used ------------------------------------------------------
    echo "\nLast used:\n";
    ok('a new view has never been used', tableViewLoad($conn, $owner, $pub)['last_used_datetime'] === null);
    tableViewTouch($conn, $owner, $pub);
    ok('using it stamps the time',        tableViewLoad($conn, $owner, $pub)['last_used_datetime'] !== null);
    if ($inA !== null) {
        refuses('you cannot stamp a view you cannot see',
            fn() => tableViewTouch($conn, $inA, $priv));
    }
    $first = tableViewList($conn, $owner, 'assets')[0]['id'] ?? 0;
    ok('the most recently used sorts first', (int)$first === $pub, "first was {$first}, expected {$pub}");

} finally {
    try {
        $conn->prepare("DELETE FROM table_views WHERE name LIKE 'ZZTV%'")->execute();
        $left = (int)$conn->query("SELECT COUNT(*) FROM table_views WHERE name LIKE 'ZZTV%'")->fetchColumn();
        echo "\n  cleanup: " . ($left === 0 ? "no ZZTV rows left\n" : "⚠️  {$left} ZZTV rows REMAIN\n");
    } catch (Exception $e) {
        echo "\n  ⚠️  cleanup failed: " . $e->getMessage() . "\n";
    }
}

echo str_repeat('=', 70) . "\n  {$pass} passed, {$fail} failed\n\n";
exit($fail === 0 ? 0 : 1);

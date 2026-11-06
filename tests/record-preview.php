<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * At-a-glance record previews (discussion #91).
 *
 * A preview is a READ, and the links that lead to one can point at records the
 * reader may not open. So the refusals matter more than the happy path, and
 * each is checked with a positive control beside it — otherwise "it refused"
 * proves nothing more than that something was broken.
 *
 * ⚠️ Touches the database. Everything it makes is named ZZPV and removed in the
 * cleanup at the bottom, including on failure.
 *
 * Run:  php tests/record-preview.php
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/i18n.php';
require_once __DIR__ . '/../includes/record_preview.php';
I18n::initFromSession();

$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}
/** The value of one labelled field in a preview, or null. */
function fieldOf(?array $p, string $label): ?string {
    foreach ($p['fields'] ?? [] as $f) {
        if ($f['label'] === $label) return $f['value'];
    }
    return null;
}

echo "\nRecord previews (#91)\n" . str_repeat('=', 70) . "\n";

$conn = connectToDatabase();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$admin = 1;

try {
    // ── 1. Every promised type answers ──────────────────────────────────────
    echo "\nEach type the discussion promised:\n";
    $probe = [
        'ticket'            => 'SELECT id FROM tickets ORDER BY id DESC LIMIT 1',
        'task'              => 'SELECT id FROM tasks WHERE parent_task_id IS NULL ORDER BY id DESC LIMIT 1',
        'change'            => 'SELECT id FROM changes ORDER BY id DESC LIMIT 1',
        'problem'           => 'SELECT id FROM problems ORDER BY id DESC LIMIT 1',
        'asset'             => 'SELECT id FROM assets ORDER BY id DESC LIMIT 1',
        'contract'          => 'SELECT id FROM contracts ORDER BY id DESC LIMIT 1',
        'knowledge_article' => 'SELECT id FROM knowledge_articles ORDER BY id DESC LIMIT 1',
    ];
    $found = [];
    foreach ($probe as $type => $sql) {
        $id = (int)$conn->query($sql)->fetchColumn();
        if (!$id) { echo "  SKIP  {$type} — nothing in the database to preview\n"; continue; }
        $found[$type] = $id;
        $p = recordPreview($conn, $admin, $type, $id);
        ok("{$type} previews", $p !== null && ($p['heading'] ?? '') !== '',
            $p === null ? 'returned null' : 'no heading');
        if ($p) {
            ok("  …and carries a link to open it", !empty($p['url']));
        }
    }

    // ── 2. The fields promised in the discussion ────────────────────────────
    echo "\nThe fields that were promised:\n";
    if (isset($found['ticket'])) {
        $p = recordPreview($conn, $admin, 'ticket', $found['ticket']);
        ok('a ticket shows its status', fieldOf($p, t('common.preview.status')) !== null);
    }
    // ⚠️ An asset that actually HAS a make and model, not whichever happens to be
    // newest. The first run asserted on the latest row, which had neither, so it
    // failed for a reason that said nothing about the code.
    $richAsset = (int)$conn->query(
        "SELECT id FROM assets WHERE manufacturer IS NOT NULL AND manufacturer <> '' ORDER BY id DESC LIMIT 1"
    )->fetchColumn();
    if ($richAsset) {
        $p = recordPreview($conn, $admin, 'asset', $richAsset);
        $labels = array_column($p['fields'] ?? [], 'label');
        ok('an asset offers make and model', in_array(t('common.preview.model'), $labels, true),
            'model did not appear: ' . implode(', ', $labels));
    } else {
        echo "  SKIP  no asset on this install has a manufacturer recorded\n";
    }
    if (isset($found['knowledge_article'])) {
        $p = recordPreview($conn, $admin, 'knowledge_article', $found['knowledge_article']);
        ok('an article returns its opening lines', array_key_exists('lead', $p));
        ok('and its lead carries no markup',
            !str_contains((string)($p['lead'] ?? ''), '<'),
            'raw HTML reached the preview');
    }

    // ── 3. Refusals — the point of the file ─────────────────────────────────
    echo "\nRefusals (each must return null):\n";
    ok('an unknown type',            recordPreview($conn, $admin, 'nonsense', 1) === null);
    ok('a record that does not exist', recordPreview($conn, $admin, 'ticket', 999999999) === null);
    ok('a zero id',                  recordPreview($conn, $admin, 'ticket', 0) === null);
    ok('a negative id',              recordPreview($conn, $admin, 'ticket', -5) === null);

    // The module gate. An analyst restricted away from a module must learn
    // nothing about its records, however they reached the link.
    $restricted = null;
    foreach ($conn->query("SELECT id FROM analysts WHERE is_active = 1 AND id <> 1")->fetchAll(PDO::FETCH_COLUMN) as $aid) {
        $allowed = getAnalystAllowedModules($conn, (int)$aid);
        if ($allowed !== null && !in_array('assets', $allowed, true)) { $restricted = (int)$aid; break; }
    }
    if ($restricted !== null && isset($found['asset'])) {
        ok('somebody without the Assets module gets nothing for an asset',
            recordPreview($conn, $restricted, 'asset', $found['asset']) === null);
        ok('POSITIVE CONTROL: an administrator still gets it',
            recordPreview($conn, $admin, 'asset', $found['asset']) !== null);
    } else {
        echo "  SKIP  no analyst on this install is restricted away from Assets\n";
    }

    // ⚠️ Whether or not there is a restricted analyst to borrow, prove the module
    // gate is CONSULTED at all. analystCanAccessModule() refuses an id of 0, so a
    // preview asked for by nobody must come back empty for every single type — a
    // type that answered would be one that skipped the gate.
    $leaked = [];
    foreach ($found as $type => $id) {
        if (recordPreview($conn, 0, $type, $id) !== null) { $leaked[] = $type; }
    }
    ok('no type answers an analyst id of 0', $leaked === [], implode(', ', $leaked));

    // ── 3b. Never STRICTER than the module's own gate ───────────────────────
    //
    // 🔴 The bug this catches shipped once already, and a live run found it, not
    // this file. Three types scoped themselves with activeTenantFilter(), which
    // answers "is this in the company I am currently LOOKING AT" — a view
    // setting, not a permission. So a ticket showed a problem pill and the
    // preview behind it refused, and the refusal was indistinguishable from a
    // genuine one.
    //
    // The rule: if the module's own analystCanAccess…() says yes, the preview
    // must answer. Records in another company are exactly where the two used to
    // disagree, so they are what this looks for.
    echo "\nA preview is never stricter than the module it belongs to:\n";
    $gates = [
        'task'    => ['tasks',    'analystCanAccessTask'],
        'change'  => ['changes',  'analystCanAccessChange'],
        'problem' => ['problems', 'analystCanAccessProblem'],
        'ticket'  => ['tickets',  'analystCanAccessTicket'],
    ];
    $checked = 0;
    foreach ($gates as $type => [$table, $gate]) {
        if (!function_exists($gate)) { continue; }
        // Prefer a record in ANOTHER company — that is where they diverged.
        $ids = $conn->query(
            "SELECT id FROM {$table} ORDER BY (tenant_id IS NULL), tenant_id DESC, id DESC LIMIT 25"
        )->fetchAll(PDO::FETCH_COLUMN);
        foreach ($ids as $rid) {
            if (!$gate($conn, $admin, (int)$rid)) continue;   // genuinely out of reach
            $checked++;
            ok("{$type} #{$rid} is allowed by {$gate}(), so it previews",
                recordPreview($conn, $admin, $type, (int)$rid) !== null,
                'the gate said yes and the preview said no');
            break;
        }
    }
    ok('POSITIVE CONTROL: this section actually examined something', $checked > 0,
       'no record passed any gate, so the assertions above were vacuous');

    // ── 4. Nothing leaks through the shape of the answer ────────────────────
    echo "\nOne answer for \"missing\" and \"not allowed\":\n";
    $a = recordPreview($conn, $admin, 'ticket', 999999998);
    $b = recordPreview($conn, $admin, 'ticket', 999999999);
    ok('two different unreachable ids are indistinguishable', $a === null && $b === null && $a === $b);

    // ── 5. Empty fields are dropped, not shown blank ────────────────────────
    echo "\nEmpty fields:\n";
    ok('a null value produces no field at all', rpField('Label', null) === null);
    ok('an empty string likewise',              rpField('Label', '') === null);
    ok('whitespace only likewise',              rpField('Label', '   ') === null);
    ok('POSITIVE CONTROL: a real value produces one',
       (rpField('Label', 'value')['value'] ?? null) === 'value');
    ok('and rpFields drops the nulls',
       count(rpFields([rpField('a', 'x'), rpField('b', null), rpField('c', 'y')])) === 2);

} finally {
    try {
        $conn->prepare("DELETE FROM tickets WHERE subject LIKE 'ZZPV%'")->execute();
        echo "\n  cleanup: nothing was created, nothing to remove\n";
    } catch (Exception $e) {
        echo "\n  ⚠️  cleanup failed: " . $e->getMessage() . "\n";
    }
}

echo str_repeat('=', 70) . "\n  {$pass} passed, {$fail} failed\n\n";
exit($fail === 0 ? 0 : 1);

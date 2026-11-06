<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Task priority indicators (GH discussion #108).
 *
 * This is a SCAN, not a behaviour test, and deliberately so.
 *
 * The bug it guards against is not a wrong output — it is a renderer deriving a
 * colour from a priority's DISPLAY NAME. That produced `class="priority-dot hoch"`
 * on a German install, matched none of the four hardcoded English rules, and drew
 * a transparent circle: no error, no fallback, nothing on screen to suggest a
 * setting had not applied. A behaviour test proves the renderer that exists today
 * is right; it cannot stop the FIFTH renderer, written next year, from reaching
 * for the name again because that is what its neighbours used to do.
 *
 * This is the same shape as GH #79 (every ticket intake path resolved its status
 * by the word 'Open') and the Watchtower counters before it. Three times now, so
 * the invariant gets a test rather than a comment:
 *
 *   1. NO stylesheet carries a per-priority-name rule.
 *   2. NO renderer interpolates a priority into a class attribute.
 *   3. Every place that draws a priority goes through TasksPriority.
 *   4. TasksPriority validates the colour and escapes the name, because both are
 *      admin-editable free text and the name is stored exactly as typed.
 *
 * Makes no database or network calls and writes nothing.
 *
 * Run:  php tests/tasks-priority.php
 */

$root = dirname(__DIR__);

$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}

/** Every file we expect to draw a task priority. */
$renderers = [
    'assets/js/tasks.js',
    'assets/js/tasks-timeline.js',
];

/**
 * Strip comments before scanning for banned code.
 *
 * These files carry long comments explaining the very construction being banned
 * — quoting `t.priority.toLowerCase()` as the thing that broke — and a scan that
 * cannot tell code from prose fails on its own documentation, which teaches the
 * next person to delete the explanation rather than keep the guard.
 *
 * ⚠️ Naive: it does not understand a `//` inside a string or a regex literal.
 * Good enough here because it is only ever used to REMOVE text before looking
 * for a banned pattern, so its failure mode is a missed hit, not a false one —
 * and the patterns below are checked against the real files on every run.
 */
function stripJsComments(string $js): string {
    $js = preg_replace('#/\*.*?\*/#s', '', $js);
    return preg_replace('#(^|\s)//.*$#m', '$1', $js);
}

echo "\nTask priority indicators (#108)\n";
echo str_repeat('-', 60) . "\n";

// ---- 1. The helper exists and is the only place the markup is built --------

$helperPath = $root . '/assets/js/tasks-priority.js';
ok('the shared renderer exists', is_file($helperPath), $helperPath);
$helper = is_file($helperPath) ? file_get_contents($helperPath) : '';

ok('the helper validates the colour as a literal hex value',
   (bool) preg_match('/\^#\[0-9a-fA-F\]/', $helper));
ok('the helper escapes the name',
   str_contains($helper, "replace(/[&<>\"']/g"));
ok('the helper still accepts the legacy on/off setting',
   str_contains($helper, 'normaliseStyle')
   && preg_match("/return 'dot'/", $helper) === 1);

// ---- 2. No stylesheet may colour a priority by its name --------------------
//
// The four rules that caused this (.priority-dot.urgent/.high/.medium/.low) are
// gone. Any per-name rule reintroduces the fault for whoever renames that value,
// so the whole SHAPE is banned rather than those four words.

foreach (glob($root . '/assets/css/*.css') as $cssFile) {
    $css  = file_get_contents($cssFile);
    $name = basename($cssFile);
    // .priority-dot.<word> / .priority-pill.<word> — a class qualified by a name.
    $bad = preg_match('/\.priority-(?:dot|pill|accent)\.[a-z]/i', $css, $m);
    ok("{$name} has no per-name priority rule", !$bad, $bad ? $m[0] : '');
}

// ---- 3. No renderer may put a priority into a class attribute --------------

foreach ($renderers as $rel) {
    $path = $root . '/' . $rel;
    ok("{$rel} exists", is_file($path), $path);
    if (!is_file($path)) continue;
    $raw = file_get_contents($path);
    $js  = stripJsComments($raw);

    // class="… ${…priority…}" — the exact construction that broke.
    $bad = preg_match('/class="[^"]*\$\{[^}]*priority[^}]*\}/i', $js, $m);
    ok("{$rel} never builds a class from a priority", !$bad, $bad ? trim($m[0]) : '');

    // .toLowerCase() applied to a priority — how the class used to be made, and
    // also what threw on a task with no priority set.
    $bad = preg_match('/priority[a-zA-Z_]*\s*(?:\|\|[^)]*)?\)?\.toLowerCase\(\)/i', $js, $m);
    ok("{$rel} never lower-cases a priority name", !$bad, $bad ? trim($m[0]) : '');

    // Anything that draws a priority must go through the helper.
    if (str_contains($raw, 'priority-dot') || str_contains($raw, 'priority-pill')) {
        ok("{$rel} draws priorities through TasksPriority",
           str_contains($raw, 'TasksPriority.'));
    }
}

// ---- 4. The pages that render priorities must load the helper --------------
//
// It has no dependencies precisely so it can sit ahead of anything; a page that
// forgets it fails loudly (TasksPriority is not defined) rather than silently,
// but catching it here is cheaper than catching it on the board.

$pages = [
    'tasks/index.php',
    'tasks/timeline/index.php',
    'tasks/settings/index.php',
];
foreach ($pages as $rel) {
    $path = $root . '/' . $rel;
    ok("{$rel} exists", is_file($path), $path);
    if (!is_file($path)) continue;
    $php = file_get_contents($path);
    ok("{$rel} loads tasks-priority.js", str_contains($php, 'tasks-priority.js'));
}

// ---- 5. The colour must be on the wire wherever a priority is ---------------
//
// A renderer reading t.priority_colour against a query that only selects
// tp.name would fall back to grey everywhere and look like a styling choice.

$queries = [
    'api/tasks/list.php' => 'the board, list and timeline',
    'api/tasks/get.php'  => 'the detail panel and its subtasks',
];
foreach ($queries as $rel => $what) {
    $path = $root . '/' . $rel;
    ok("{$rel} exists", is_file($path), $path);
    if (!is_file($path)) continue;
    $php = file_get_contents($path);
    $names   = preg_match_all('/tp\.name AS priority\b/', $php);
    $colours = preg_match_all('/tp\.colour AS priority_colour\b/', $php);
    ok("{$rel} returns a colour for every priority it returns ({$what})",
       $names > 0 && $colours >= $names, "names={$names} colours={$colours}");
}

// ---- 6. The stored placement is a registry value ---------------------------

$save = file_get_contents($root . '/api/tasks/save_settings.php');
ok('save_settings normalises the placement before storing it',
   str_contains($save, '$priorityStyles')
   && str_contains($save, "in_array(\$p, \$priorityStyles, true)"));

$get = file_get_contents($root . '/api/tasks/get_settings.php');
ok('get_settings reads the legacy boolean as a placement',
   str_contains($get, "empty(\$p) ? 'off' : 'dot'"));

// ---- 7. The default lookup must not resolve by name (GH #79) ---------------

$svc = file_get_contents($root . '/includes/services/tasks.php');
ok('the tasks service resolves defaults by is_default, not by name',
   str_contains($svc, "ORDER BY is_default DESC, display_order, id")
   && !preg_match("/lookupDefault\(\\\$conn, '[a-z_]+', '[A-Z]/", $svc));
ok('the tasks service ignores deactivated defaults',
   preg_match('/lookupDefault.*?is_active = 1/s', $svc) === 1);

echo str_repeat('-', 60) . "\n";
echo "  {$pass} passed, {$fail} failed\n\n";
exit($fail > 0 ? 1 : 0);

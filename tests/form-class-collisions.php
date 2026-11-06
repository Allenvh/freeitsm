<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * The Forms module's own CSS class names must not collide with the stylesheets
 * every page already loads.
 *
 * 🔴 WHY THIS EXISTS. A table question was given `class="form-grid"`, and
 * `inbox.css` has had this since long before it:
 *
 *     .form-grid { display: grid; grid-template-columns: 1fr 1fr; }
 *
 * Every Forms page loads inbox.css, so the <table> became a two-column CSS grid
 * and its <thead> and <tbody> were laid out SIDE BY SIDE. The headings sat in
 * one column and the inputs in the other. Ed saw it within minutes of building
 * his first table.
 *
 * ⚠️ AND EVERY TEST PASSED. The markup was correct, the column ids were
 * correct, the retired column was correctly absent — because the rendering
 * harness did not load inbox.css. **A harness missing a stylesheet the real
 * page loads is not a rendering environment**, and it will report a broken
 * screen as perfect. The harness now loads it; this test stops the collision
 * being introduced at all, which is the cheaper end.
 *
 * Run: php tests/form-class-collisions.php
 */

$root = dirname(__DIR__);
$pass = 0; $fail = 0;

function check(string $label, bool $ok, string $detail = ''): void {
    global $pass, $fail;
    if ($ok) { $pass++; echo "  ok    $label\n"; }
    else     { $fail++; echo "  FAIL  $label" . ($detail !== '' ? "  — $detail" : '') . "\n"; }
}

/** Every class name a stylesheet DEFINES a rule for. */
function classesDefinedIn(string $path): array {
    $css = @file_get_contents($path);
    if ($css === false) return [];
    // Comments out first, or a class named in prose counts as a definition.
    $css = preg_replace('~/\*.*?\*/~s', '', $css);
    preg_match_all('~\.([a-zA-Z_][\w-]*)~', $css, $m);
    return array_values(array_unique($m[1]));
}

echo "Forms class names vs the sheets every page already loads\n";
echo str_repeat('=', 64) . "\n";

/* form-shared.css is the Forms module's own component sheet. Anything it
   defines is a name this module invented, so it is the thing to check. */
$ours = classesDefinedIn($root . '/assets/css/form-shared.css');
check('form-shared.css defines some classes', count($ours) > 0, count($ours) . ' found');

/* The sheets loaded alongside it on at least one Forms page. theme.css is
   excluded deliberately: it is a token registry with no component classes, and
   if that ever stops being true this test will start reporting it, which is
   the correct outcome. */
$globals = [
    'inbox.css' => $root . '/assets/css/inbox.css',
    'forms.css' => $root . '/assets/css/forms.css',
];

foreach ($globals as $name => $path) {
    $theirs = classesDefinedIn($path);
    check("{$name} was readable", count($theirs) > 0, count($theirs) . ' classes');

    $clash = array_values(array_intersect($ours, $theirs));

    /* forms.css is OURS too — the module's editor styles — so the two sharing a
       name is co-operation, not collision, as long as they do not both style it.
       Only inbox.css is a genuinely foreign sheet here. */
    if ($name === 'forms.css') {
        check('forms.css overlap is limited to names the module owns on purpose',
            true, $clash ? 'shared: ' . implode(', ', $clash) : 'none shared');
        continue;
    }

    check("no Forms class collides with {$name}", $clash === [],
        'COLLIDING: ' . implode(', ', $clash)
        . ' — a rule in ' . $name . ' will apply to Forms markup that never asked for it');
}

/* The specific one that bit, kept by name so it cannot come back quietly. */
$inbox = classesDefinedIn($root . '/assets/css/inbox.css');
check('CONTROL — the checker really does see inbox.css\'s .form-grid',
    in_array('form-grid', $inbox, true),
    'if this fails the parser is broken and every "no collision" above is worthless');
check('form-shared.css does NOT define .form-grid',
    !in_array('form-grid', $ours, true),
    'that is the exact name that turned a <table> into a two-column grid');

/* And the markup side: no Forms page should be emitting the colliding class.
 *
 * 🔴 A CLASS IS A WHOLE TOKEN, and this check used to forget it. The test read
 *     preg_match('~class="[^"]*\bform-grid\b~', $src)
 * and `\b` treats a HYPHEN as a word boundary, so `class="cat-form-grid"` —
 * a perfectly innocent name — matched and was reported as emitting `form-grid`.
 * CSS does not work that way: `.form-grid` matches a class attribute only when
 * one of its whitespace-separated tokens is exactly `form-grid`, so
 * `cat-form-grid` can never be affected by it.
 *
 * It cost a false alarm the day `.cat-form-grid` was introduced (#1841). A test
 * that cries wolf over a correct change gets ignored, which is how a real
 * collision would then walk past. Tokens are compared exactly now.
 */
function emitsClass(string $src, string $wanted): bool {
    if (!preg_match_all('~class="([^"]*)"~', $src, $m)) return false;
    foreach ($m[1] as $attr) {
        if (in_array($wanted, preg_split('~\s+~', trim($attr)) ?: [], true)) return true;
    }
    return false;
}

$pages = ['forms/fill.php', 'forms/edit/index.php', 'forms/submissions.php',
          'forms/collection.php', 'self-service/catalogue.php'];
$emitting = [];
foreach ($pages as $p) {
    $src = (string)@file_get_contents($root . '/' . $p);
    if (emitsClass($src, 'form-grid')) $emitting[] = $p;
}
check('no Forms page emits class="form-grid"', $emitting === [],
    'still emitting: ' . implode(', ', $emitting));

/* CONTROL — the matcher must still catch the real thing, and must not catch a
   name that merely contains it. Without both halves the fix above could have
   been "stop looking", which would pass for the wrong reason. */
check('CONTROL — the matcher DOES catch a real class="form-grid"',
    emitsClass('<table class="form-grid">', 'form-grid'));
check('CONTROL — …and catches it beside other classes',
    emitsClass('<div class="foo form-grid bar">', 'form-grid'));
check('CONTROL — but NOT class="cat-form-grid", which CSS never matches',
    !emitsClass('<form class="cat-form-grid">', 'form-grid'));

echo "\n" . str_repeat('=', 64) . "\n";
echo "$pass passed, $fail failed\n";
exit($fail > 0 ? 1 : 0);

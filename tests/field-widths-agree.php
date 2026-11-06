<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * The list of permitted field widths is written down THREE times, and all three
 * must agree.
 *
 *   includes/services/forms.php   FormsService::FIELD_WIDTHS   — what may be saved
 *   assets/js/form-logic.js       WIDTHS                       — what the fillers render
 *   forms/edit/index.php          FIELD_WIDTHS                 — what the builder offers
 *
 * 🔴 WHY THIS TEST EXISTS. Three hand-maintained lists that have to agree is
 * exactly the shape that produced #121 (the index backfill list drifting from
 * freeitsm.sql, three times) and the lesson written up there: **a guard a human
 * has to remember is not a guard.**
 *
 * The failure would be quiet. Add a width to the builder and not the service,
 * and the picker offers a choice that is refused on save. Add it to the service
 * and not to form-logic, and a saved width renders as full. Neither errors.
 *
 * They are not derived from one source because the three live in three
 * languages loaded by different pages; a test is the cheaper guard.
 *
 * Run: php tests/field-widths-agree.php
 */

$root = dirname(__DIR__);
$pass = 0; $fail = 0;

function check(string $label, bool $ok, string $detail = ''): void {
    global $pass, $fail;
    if ($ok) { $pass++; echo "  ok    $label\n"; }
    else     { $fail++; echo "  FAIL  $label" . ($detail !== '' ? "  — $detail" : '') . "\n"; }
}

/** Pull a bracketed integer list out of a file by regex, or return null. */
function intList(string $path, string $pattern): ?array {
    $src = @file_get_contents($path);
    if ($src === false) return null;
    if (!preg_match($pattern, $src, $m)) return null;
    $out = array_map('intval', array_map('trim', explode(',', $m[1])));
    return $out ?: null;
}

echo "Field widths — two lists, one meaning\n";
echo str_repeat('=', 60) . "\n";

require_once $root . '/includes/services/forms.php';
$service = FormsService::FIELD_WIDTHS;

$logic = intList($root . '/assets/js/form-logic.js',  '/var\s+WIDTHS\s*=\s*\[([0-9,\s]+)\]/');

check('the service defines a width list', !empty($service));
check('form-logic.js defines one',        $logic !== null,   'pattern did not match — was the variable renamed?');

if ($logic !== null) check('form-logic agrees with the service', $service === $logic,
    'service=' . implode(',', $service) . '  logic=' . implode(',', $logic));

/* ⭐ THERE USED TO BE A THIRD LIST, in forms/edit/index.php. The builder now
   reads FormLogic's instead of declaring its own, so the drift it could suffer
   is gone rather than guarded. What has to be asserted now is that it really
   does defer — a future edit that reintroduces a literal list here would
   silently recreate the third source this test was written for. */
$builderSrc = (string)@file_get_contents($root . '/forms/edit/index.php');
check('the builder defers to FormLogic for widths',
    (bool)preg_match('/const\s+FIELD_WIDTHS\s*=\s*FormLogic\.FIELD_WIDTHS\s*;/', $builderSrc),
    'the builder should read FormLogic.FIELD_WIDTHS, not declare its own');
check('the builder declares NO literal width list of its own',
    !preg_match('/const\s+FIELD_WIDTHS\s*=\s*\[/', $builderSrc),
    'a literal list reappeared in forms/edit/index.php — that is the third source again');
check('the builder loads form-logic.js (or FormLogic is undefined at runtime)',
    str_contains($builderSrc, 'assets/js/form-logic.js'),
    'the builder reads FormLogic but never loads it');

/* The default must be on the list, and it must be the widest — a default that
   is not full width would silently re-lay out every existing form, since every
   pre-existing field has no width at all. */
check('the default is one of the permitted widths',
    in_array(FormsService::FIELD_WIDTH_DEFAULT, $service, true));
check('the default is the FULL row (absent must mean unchanged)',
    FormsService::FIELD_WIDTH_DEFAULT === max($service),
    'default=' . FormsService::FIELD_WIDTH_DEFAULT . ' max=' . max($service));

/* Every width must have a PARTNER that completes the row — 9 goes with 3,
   8 with 4, 6 with 6. Otherwise a width can be chosen that can never sit
   beside anything, which is a picker offering a choice with no purpose.
   ⚠️ NOT "12 is divisible by w": 9 and 8 are on the list precisely because
   the asymmetric pairs are what a real document wants (Area | Date is 8+4,
   not 6+6), and neither divides 12. That was this test's own first, wrong
   assumption. */
foreach ($service as $w) {
    check("width $w is in range", $w > 0 && $w <= 12);
    if ($w === 12) continue;                       // the whole row needs no partner
    check("width $w has a partner (" . (12 - $w) . ") that completes the row",
        in_array(12 - $w, $service, true));
}

/* ---- The GRID CONTAINER, which is what makes a width mean anything ---------
   🔴 WHY THIS SECTION WAS ADDED (#1841). Guarding the width LIST turned out to
   guard only half the mechanism. Every surface emits `data-width="6"` from the
   shared walk in FormRender — but twelfths do nothing unless the element's
   PARENT is the 12-column grid, and the portal's <form> carried
   `class="cat-form-table"`, a class defined in no stylesheet in the repository.
   Its real grid, `.cat-form-grid`, sat in self-service.css fully written and
   referenced by nothing. So every width was calculated, written into the markup
   and silently dropped, and the portal drew as one long column while the
   analyst filler laid the same form out in two.

   Nothing could catch it: an unrecognised class is not an error in CSS, and the
   markup contained no mistake a reader would see. The guard has to assert the
   two halves MEET — that the class the page emits is the class the stylesheet
   defines, and that it carries a rule for every width that may be saved. */
/* 🔴 A class is a WHOLE TOKEN. Matching `\bcat-form-grid\b` inside class="..."
   looks right and is not: a hyphen is a regex word boundary, so the pattern
   also matches `x-cat-form-grid`, which CSS would never style. Compared exactly,
   the way the browser does. Same correction as tests/form-class-collisions.php,
   which cried wolf over `.cat-form-grid` for precisely this reason. */
function emitsClass(string $src, string $wanted): bool {
    if (!preg_match_all('~class="([^"]*)"~', $src, $m)) return false;
    foreach ($m[1] as $attr) {
        if (in_array($wanted, preg_split('~\s+~', trim($attr)) ?: [], true)) return true;
    }
    return false;
}

$surfaces = [
    'analyst filler'  => [
        'markup' => 'forms/fill.php',
        'class'  => 'fill-grid',
        // Its grid is an inline <style> in the page itself, so markup and CSS are one file.
        'css'    => 'forms/fill.php',
        'loader' => 'forms/fill.php',
        'href'   => null,
    ],
    'portal'          => [
        'markup' => 'self-service/catalogue.php',
        'class'  => 'cat-form-grid',
        'css'    => 'assets/css/self-service.css',
        // The portal's stylesheets are pulled in by the shared header, not the page.
        'loader' => 'self-service/includes/header.php',
        'href'   => 'assets/css/self-service.css',
    ],
    'builder preview' => [
        'markup' => 'forms/edit/index.php',
        'class'  => 'preview-grid',
        'css'    => 'assets/css/forms.css',
        'loader' => 'forms/edit/index.php',
        'href'   => 'assets/css/forms.css',
    ],
];

foreach ($surfaces as $name => $s) {
    $markup = (string)@file_get_contents($root . '/' . $s['markup']);
    $css    = (string)@file_get_contents($root . '/' . $s['css']);
    $loader = (string)@file_get_contents($root . '/' . $s['loader']);
    $cls    = preg_quote($s['class'], '/');

    /* The class is really emitted. Rename it in the markup alone and this fails,
       which is the half that was missing when catalogue.php said cat-form-table.

       🔴 Matched inside a class="..." ATTRIBUTE, not anywhere in the file. The
       first draft of this check searched the whole source, and the explanatory
       comment naming `.cat-form-grid` — added by the very fix this guards —
       satisfied it on its own: run against the broken markup as a control, it
       passed. A guard that its own documentation can satisfy is not a guard. */
    check("$name: emits the container class '{$s['class']}'",
        emitsClass($markup, $s['class']),
        "no element carries class=\"{$s['class']}\" in {$s['markup']} — was the container renamed?");

    /* ...and the stylesheet really defines it. Rename it in the CSS alone and
       this fails instead. Between them the two names cannot drift apart. */
    check("$name: a stylesheet defines .{$s['class']} as a grid",
        (bool)preg_match('/\.' . $cls . '\s*\{[^}]*display\s*:\s*grid/s', $css),
        "no '.{$s['class']} { display: grid }' in {$s['css']}");

    // Defining it somewhere the page never loads would be the same bug again.
    if ($s['href'] !== null) {
        check("$name: actually loads {$s['css']}",
            str_contains($loader, $s['href']),
            "{$s['loader']} does not reference {$s['href']}");
    }

    /* Every saveable width needs a rule on THIS container. A width added to the
       service and to two of the three grids renders full-width on the third —
       silently, and only on one surface, which is the shape that took a user
       report to find. */
    foreach ($service as $w) {
        if ($w === FormsService::FIELD_WIDTH_DEFAULT) continue;   // no width = the whole row
        check("$name: .{$s['class']} has a rule for width $w",
            (bool)preg_match('/\.' . $cls . '\s*>\s*\[data-width="' . $w . '"\]/', $css),
            "no '.{$s['class']} > [data-width=\"$w\"]' in {$s['css']}");
    }
}

/* ---- How wide the CARD is, which is what twelfths are twelfths OF ----------
   A field's width is relative to the form's own width, so two surfaces that
   agree about the twelfths and disagree about the card still draw the same form
   differently. They did: 860px on the analyst filler, 720px on the portal, so
   every field was 16% narrower for the customer it was written for.

   🔴 A PHANTOM TOKEN IS THE RISK HERE. `var(--form-card-max)` with nothing
   defining it does not error — it silently falls back, and if there were no
   fallback the card would have no max-width at all and run the full width of the
   screen. So the token's DEFINITION is asserted, not just its use. */
$shared = (string)@file_get_contents($root . '/assets/css/form-shared.css');
check('form-shared.css defines --form-card-max',
    (bool)preg_match('/--form-card-max\s*:\s*\d+px/', $shared),
    'the token both filling surfaces read is not defined anywhere');

foreach ([
    'analyst filler' => ['forms/fill.php',               'forms/fill.php'],
    'portal'         => ['self-service/catalogue.php',   'self-service/includes/header.php'],
] as $name => [$file, $loader]) {
    $src  = (string)@file_get_contents($root . '/' . $file);
    $load = (string)@file_get_contents($root . '/' . $loader);

    check("$name: takes its card width from the shared token",
        str_contains($src, 'var(--form-card-max'),
        "$file should read var(--form-card-max), not carry its own number");

    /* A literal alongside the token is how this drifts back: somebody nudges one
       surface, the other keeps the token, and they disagree again. */
    check("$name: declares no literal max-width for the form card",
        !preg_match('/\.(fill-content|cat-form)\s*\{[^}]*max-width\s*:\s*\d+px/s', $src),
        "$file still hard-codes a card width");

    check("$name: loads form-shared.css, where the token lives",
        str_contains($load, 'form-shared.css'),
        "$loader does not load it, so the token would be undefined at runtime");
}

/* CONTROL — the checker must be able to FAIL. A test that only ever passes is
   not evidence, and this one is entirely pattern-matching. */
$fakeOk = ([12, 6] === [12, 6]);
$fakeBad = ([12, 6] === [12, 5]);
check('CONTROL — identical lists compare equal', $fakeOk);
check('CONTROL — differing lists do NOT compare equal', !$fakeBad);

echo str_repeat('=', 60) . "\n";
echo "$pass passed, $fail failed\n";
exit($fail === 0 ? 0 : 1);

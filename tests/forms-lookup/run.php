<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Forms — lookup fields: scoping, portal gates, and field-type drift.
 *
 * A lookup field answers "which one?" by searching records the app already
 * holds. That makes it the first form field whose list of possible answers is
 * built at answer time out of live, company-scoped data — so two things can go
 * wrong that no previous field type could:
 *
 *  1. SCOPING. The search must never return a record the person asking is not
 *     allowed to see. A form is a place where a customer types, so "the widget
 *     only shows their own kit" has to be true on the server, not in the UI.
 *
 *  2. TAMPERING. The posted answer is an id. Nothing stops a crafted request
 *     naming someone else's asset, and if we accepted it the submission would
 *     render that asset's name to an analyst who would reasonably believe it.
 *     The submit-time guard is the generalisation of the older rule that a
 *     dropdown must be answered with one of ITS OWN choices.
 *
 * And one thing that has bitten this module before:
 *
 *  3. DRIFT. A field type has to be added in several places at once — the
 *     service whitelist, the AI generator's whitelist AND its prompt, the
 *     shared JS evaluator, the builder. The prompt is prose, so nothing fails
 *     when it falls behind; it just quietly stops offering the type, or offers
 *     one that no longer exists. This suite reads all five and compares them.
 *
 *   php tests/forms-lookup/run.php
 *
 * ⚠️ Every "it refused" assertion is paired with a positive control that the
 * same call ACCEPTS something legitimate. A function that refuses everything —
 * because a column name is wrong, say — would otherwise look like a clean pass.
 *
 * Read-only: it searches existing records and never writes.
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/services/forms.php';

$pass = 0; $fail = 0; $skip = 0;

function check(string $what, bool $ok, string $detail = ''): void
{
    global $pass, $fail;
    if ($ok) { $pass++; echo "  [PASS] $what\n"; }
    else     { $fail++; echo "  [FAIL] $what" . ($detail !== '' ? " — $detail" : '') . "\n"; }
}
function skipped(string $what, string $why): void
{
    global $skip;
    $skip++;
    echo "  [SKIP] $what — $why\n";
}

$conn = connectToDatabase();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// ─────────────────────────────────────────────────────────────────────────────
echo "\n1. Every source in the registry actually resolves\n";
// A wrong column name here is invisible: the search just returns nothing, which
// reads as "no matches" rather than as a broken source. So each source is asked
// to run its real query once, with no term and no scoping, and must not throw.
foreach (FormsService::LOOKUP_SOURCES as $key => $meta) {
    try {
        $rows = FormsService::lookupSearch($conn, $key, '', null, 5);
        $shaped = true;
        foreach ($rows as $r) {
            if (!array_key_exists('id', $r) || !array_key_exists('label', $r)) { $shaped = false; break; }
        }
        check("source '$key' queries {$meta['table']} without error", true, count($rows) . ' row(s)');
        check("source '$key' returns {id,label} rows", $shaped);
    } catch (Throwable $e) {
        check("source '$key' queries {$meta['table']} without error", false, $e->getMessage());
    }
}
// The negative control for the above: an unknown source must return nothing
// rather than fall through to some default table.
check("an unknown source returns nothing", FormsService::lookupSearch($conn, 'not_a_source', '', null, 5) === []);

// ─────────────────────────────────────────────────────────────────────────────
echo "\n2. Company scoping\n";
// ⚠️ For assets (as for tickets and changes) `tenant_id IS NULL` does NOT mean
// "shared" — it means "unassigned, treat as the DEFAULT company's". That is the
// opposite of Knowledge, and it is the whole reason this section has to find its
// owning company two different ways: on a single-company install every row is
// NULL and belongs, in effect, to Default.
$owner = $conn->query("SELECT tenant_id, COUNT(*) c FROM assets
                        WHERE tenant_id IS NOT NULL GROUP BY tenant_id
                        ORDER BY c DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
$ownerId = $owner ? (int)$owner['tenant_id'] : null;
if ($ownerId === null && function_exists('getDefaultTenantId')) {
    // No asset names a company, so the Default company is the one that owns them.
    $unassigned = (int)$conn->query("SELECT COUNT(*) FROM assets WHERE tenant_id IS NULL")->fetchColumn();
    $default    = getDefaultTenantId($conn);
    if ($unassigned > 0 && $default !== null) $ownerId = (int)$default;
}
if ($ownerId === null) {
    skipped('company scoping', 'no assets on this database');
} else {
    $other = $conn->query("SELECT id FROM tenants WHERE id <> {$ownerId} LIMIT 1")->fetchColumn();

    $mine = FormsService::lookupSearch($conn, 'asset', '', [$ownerId], 50);
    // POSITIVE CONTROL. Without this, "the other company saw none" is equally
    // true of a search that is simply broken.
    check("the owning company sees its own assets", count($mine) > 0, count($mine) . ' returned');

    if ($other === false) {
        skipped('another company sees none of them', 'only one company on this database');
    } else {
        $theirs = FormsService::lookupSearch($conn, 'asset', '', [(int)$other], 50);
        $mineIds  = array_column($mine, 'id');
        $overlap  = array_intersect($mineIds, array_column($theirs, 'id'));
        check("another company sees none of the owner's assets", $overlap === [],
              count($overlap) . ' leaked');
    }

    // An EMPTY array means "no companies at all", and must not be mistaken for
    // "unrestricted" — the difference between a customer with no company and a
    // customer who can see everything.
    check("an empty company list returns nothing", FormsService::lookupSearch($conn, 'asset', '', [], 50) === []);
    // ...paired with its control: null DOES mean unrestricted.
    check("null companies means unrestricted", count(FormsService::lookupSearch($conn, 'asset', '', null, 50)) > 0);

    // ── 3. Tampering ─────────────────────────────────────────────────────────
    echo "\n3. The submit-time guard on a posted id\n";
    $mineId = (int)$mine[0]['id'];
    check("a genuine in-scope id is accepted",
          FormsService::lookupValueAllowed($conn, 'asset', $mineId, [$ownerId]));
    if ($other !== false) {
        check("an id from another company is refused",
              !FormsService::lookupValueAllowed($conn, 'asset', $mineId, [(int)$other]));
    }
    check("an id that does not exist is refused",
          !FormsService::lookupValueAllowed($conn, 'asset', 999999999, null));
    check("id 0 is refused", !FormsService::lookupValueAllowed($conn, 'asset', 0, null));
    check("an unknown source is refused whatever the id",
          !FormsService::lookupValueAllowed($conn, 'not_a_source', $mineId, null));
}

// ─────────────────────────────────────────────────────────────────────────────
echo "\n4. The two portal gates, each refusing on its own\n";
// Exposing records to customers needs BOTH: the source must be portal-safe, and
// the field must be ticked. Each is tested with the other satisfied, so neither
// can be carrying the other.
$safeSource = null; $unsafeSource = null;
foreach (FormsService::LOOKUP_SOURCES as $k => $m) {
    if (!empty($m['portal_safe'])) { $safeSource = $safeSource ?? $k; }
    else                           { $unsafeSource = $unsafeSource ?? $k; }
}
$fieldWith = function (?string $src, bool $ticked): array {
    $cfg = [];
    if ($src !== null) $cfg['lookup_source'] = $src;
    if ($ticked)       $cfg['portal_lookup'] = true;
    return ['field_type' => 'lookup', 'config' => json_encode($cfg)];
};
if ($safeSource === null) {
    skipped('portal gates', 'no portal-safe source in the registry');
} else {
    check("a portal-safe source that IS ticked is allowed",
          FormsService::lookupPortalAllowed($fieldWith($safeSource, true)));
    check("gate 1: the same source UNticked is refused",
          !FormsService::lookupPortalAllowed($fieldWith($safeSource, false)));
    if ($unsafeSource === null) {
        skipped('gate 2', 'every source is portal-safe, so nothing to refuse');
    } else {
        check("gate 2: a non-portal-safe source is refused even when ticked",
              !FormsService::lookupPortalAllowed($fieldWith($unsafeSource, true)));
    }
    check("a lookup with no source at all is refused",
          !FormsService::lookupPortalAllowed($fieldWith(null, true)));
}

// ─────────────────────────────────────────────────────────────────────────────
echo "\n5. Field types agree across every place that lists them\n";
// The reason this is a test and not a comment: the AI prompt is prose. When it
// falls behind nothing breaks — the generator simply stops offering a type, or
// keeps offering one that was removed, and the first person to notice is a user
// whose form came back wrong.
$read = function (string $rel): string {
    $p = __DIR__ . '/../../' . $rel;
    return is_readable($p) ? (string)file_get_contents($p) : '';
};
$sorted = function (array $a): array { $a = array_values(array_unique($a)); sort($a); return $a; };

$sources = [];
$sources['the service whitelist'] = FormsService::FIELD_TYPES;

$ai = $read('api/forms/ai_generate.php');
if (preg_match('/\$allowedTypes\s*=\s*\[(.*?)\];/s', $ai, $m)) {
    preg_match_all("/'([a-z]+)'/", $m[1], $w);
    $sources["the AI generator's whitelist"] = $w[1];
}
if (preg_match('/# FIELD TYPES(.*?)# RULES/s', $ai, $m)) {
    preg_match_all('/^- "([a-z]+)"/m', $m[1], $w);
    $sources["the AI prompt's documented list"] = $w[1];
}

$js = $read('assets/js/form-logic.js');
if (preg_match('/TYPES\s*[:=]\s*\[(.*?)\]/s', $js, $m)) {
    preg_match_all("/'([a-z]+)'/", $m[1], $w);
    $sources['the shared JS evaluator'] = $w[1];
}

$builder = $read('forms/edit/index.php');
if (preg_match('/const known\s*=\s*\[(.*?)\];/s', $builder, $m)) {
    preg_match_all("/'([a-z]+)'/", $m[1], $w);
    $sources["the builder's known types"] = $w[1];
}

// Five places were expected. If a rename means one is no longer found, that is
// itself the failure — silently checking four of five is how drift survives.
check("all five type lists were located", count($sources) === 5,
      'found: ' . implode(', ', array_keys($sources)));

$reference = $sorted(FormsService::FIELD_TYPES);
foreach ($sources as $name => $list) {
    if ($name === 'the service whitelist') continue;
    $got     = $sorted($list);
    $missing = array_diff($reference, $got);
    $extra   = array_diff($got, $reference);
    check("$name matches the service whitelist", !$missing && !$extra,
          ($missing ? 'missing: ' . implode(',', $missing) . ' ' : '')
        . ($extra   ? 'unknown: ' . implode(',', $extra) : ''));
}
// Negative control for the comparison itself: it must be capable of failing.
check("the drift check would notice a difference",
      $sorted(array_merge($reference, ['zzz_not_a_type'])) !== $reference);

// The lookup sources the prompt names must be ones we serve, for the same
// reason — a prompt that offers "contract" produces fields that never search.
if (preg_match('/# FIELD TYPES(.*?)# RULES/s', $ai, $m)
    && preg_match('/^- "lookup".*$/m', $m[1], $line)) {
    preg_match_all('/"(asset|cmdb|user|[a-z_]+)" \(/', $line[0], $named);
    $unknown = array_diff($sorted($named[1]), $sorted(array_keys(FormsService::LOOKUP_SOURCES)));
    check("every lookup source named in the prompt exists", $unknown === [],
          implode(',', $unknown));
} else {
    check("the prompt documents the lookup type", false, 'no "lookup" line found');
}

echo "\n" . str_repeat('─', 60) . "\n";
echo "  {$pass} passed, {$fail} failed" . ($skip ? ", {$skip} skipped" : '') . "\n";
exit($fail > 0 ? 1 : 0);

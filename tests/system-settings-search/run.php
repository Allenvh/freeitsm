<?php
/* 🔴 NEVER OVER THE WEB - see tests/README.md. Read-only, but every test refuses alike. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * The System landing page's settings search (#2293).
 *
 *   php tests/system-settings-search/run.php
 *
 * systemAreaSettings() lists, per System card, the headings inside it, and the
 * search links to each one as <card url>#<id>. Nothing else ties that list to
 * the pages, so this checks they still agree:
 *   - every card url is a real System area
 *   - every id is on exactly one <h2>/<h3> in that page, so the link lands
 *   - every heading key exists in English, so the search has words to match
 *
 * A heading renamed or deleted without updating the list fails here, instead of
 * leaving a search result that opens the page and goes nowhere.
 */

chdir(dirname(__DIR__, 2));
require_once 'config.php';
require_once 'includes/i18n.php';
require_once 'system/includes/areas.php';

$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; return; }
    $fail++; echo "  FAIL  $what" . ($detail !== '' ? "  ($detail)" : '') . "\n";
}

$areaUrls = array_column(getSystemAreas(), 'url');
$total = 0;
foreach (systemAreaSettings() as $url => $list) {
    ok("$url is a System area", in_array($url, $areaUrls, true));
    $file = 'system/' . $url . 'index.php';
    $src  = is_file($file) ? file_get_contents($file) : '';
    ok("$file exists", $src !== '');
    $seen = [];
    foreach ($list as [$id, $key]) {
        $total++;
        ok("$url#$id is listed once", !isset($seen[$id]));
        $seen[$id] = true;
        $n = preg_match_all('~<h[23][^>]*\bid="' . preg_quote($id, '~') . '"~', $src);
        ok("$url#$id is on exactly one heading", $n === 1, "found $n");
        $all = preg_match_all('~\bid=["\']' . preg_quote($id, '~') . '["\']~', $src);
        ok("$url#$id is unique on the page", $all === 1, "found $all");
        if ($key[0] !== '=') {
            ok("$url#$id label $key exists", t($key) !== $key);
        }
    }
}
ok('the list is not empty', $total > 0);

echo "\n$pass passed, $fail failed ($total settings across " . count(systemAreaSettings()) . " cards)\n";
exit($fail > 0 ? 1 : 0);

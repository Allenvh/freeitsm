<?php
/* 🔴 COMMAND LINE ONLY - see scripts/.htaccess. Over HTTP this would run for anyone, unauthenticated. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
/**
 * Per-locale translator material, extracted from what the locale already says.
 *
 *   php scripts/i18n_glossary.php <locale>... [--out DIR]     (DIR defaults to .i18n-work)
 *
 * Writes, under DIR:
 *   GLOSSARY.<loc>.tsv   english <TAB> translation <TAB> uses  - terms the locale has settled
 *   VOICE.<loc>.txt      a sample of the locale's longer existing strings, for register
 *
 * A term = a short English value (2-32 chars, no placeholders/HTML/newlines) that
 * has a twin in the locale, used twice or more, where one rendering holds >= 60%
 * of the uses - the rule in the wiki's Adding-a-Language guide, §3. Keys are
 * never consulted: a glossary fixes terms per English STRING, so check it for
 * clashes with your own rules before launching a wave.
 *
 * Read-only with respect to lang/.
 */
chdir(dirname(__DIR__));
require __DIR__ . '/i18n_lib.php';
$out = dirname(__DIR__) . '/.i18n-work';
$locs = [];
$args = array_slice($argv, 1);
for ($i = 0; $i < count($args); $i++) {
    if ($args[$i] === '--out') { $out = rtrim($args[++$i], '/\\'); continue; }
    $locs[] = $args[$i];
}
if (!$locs) { fwrite(STDERR, "usage: i18n_glossary.php <locale>... [--out DIR]\n"); exit(2); }
@mkdir($out, 0777, true);
foreach ($locs as $loc) {
    if (!is_dir("lang/$loc")) { fwrite(STDERR, "no lang/$loc\n"); continue; }
    $groups = [];   // lowercased english => [english => text, renders => [value => count]]
    $voice = [];
    foreach (glob('lang/en/*.php') as $enPath) {
        $ns = basename($enPath, '.php');
        if (!is_file("lang/$loc/$ns.php")) continue;
        $en = i18nFlatten(i18nLoad($enPath));
        $lc = i18nFlatten(i18nLoad("lang/$loc/$ns.php"));
        foreach ($en as $k => $ev) {
            if (!isset($lc[$k]) || !is_string($ev) || !is_string($lc[$k])) continue;
            $ev = trim($ev); $lv = trim($lc[$k]);
            if ($ev === '' || $lv === '') continue;
            $len = mb_strlen($ev);
            if ($len >= 60 && $len <= 220 && !str_contains($ev, '<') && count($voice) < 400) {
                $voice[] = [$ns, $ev, $lv];
            }
            if ($len < 2 || $len > 32) continue;
            if (preg_match('/[{}%<>\n]/', $ev)) continue;
            if (!preg_match('/\p{L}/u', $ev)) continue;
            $g = mb_strtolower($ev);
            $groups[$g]['english'] = $ev;
            $groups[$g]['renders'][$lv] = ($groups[$g]['renders'][$lv] ?? 0) + 1;
        }
    }
    $terms = [];
    foreach ($groups as $g) {
        $total = array_sum($g['renders']);
        if ($total < 2) continue;
        arsort($g['renders']);
        $top = array_key_first($g['renders']);
        if ($g['renders'][$top] / $total < 0.6) continue;
        $terms[] = [$g['english'], $top, $total];
    }
    usort($terms, fn($a, $b) => $b[2] <=> $a[2] ?: strcasecmp($a[0], $b[0]));
    $terms = array_slice($terms, 0, 700);
    $tsv = "english\ttranslation\tuses\n";
    foreach ($terms as $t) $tsv .= $t[0] . "\t" . $t[1] . "\t" . $t[2] . "\n";
    file_put_contents("$out/GLOSSARY.$loc.tsv", $tsv);
    // The voice sample: 24 longer strings spread across namespaces, so the
    // translator hears how this locale phrases a sentence, not just a label.
    shuffle($voice);
    $byNs = [];
    foreach ($voice as $v) { if (count($byNs[$v[0]] ?? []) < 3) $byNs[$v[0]][] = $v; }
    $sample = $byNs ? array_slice(array_merge(...array_values($byNs)), 0, 24) : [];
    $txt = "How $loc already phrases a sentence (English, then the locale's existing translation):\n\n";
    foreach ($sample as $v) $txt .= "EN: {$v[1]}\n$loc: {$v[2]}\n\n";
    file_put_contents("$out/VOICE.$loc.txt", $txt);
    printf("%-6s %4d terms, %2d voice samples -> %s\n", $loc, count($terms), count($sample), $out);
}

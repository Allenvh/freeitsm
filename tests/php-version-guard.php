<?php
/* 🔴 NEVER OVER THE WEB. See tests/web-exposure-guard.php. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * An old PHP gets a sentence, not a parse error.
 *
 *   php tests/php-version-guard.php
 *
 * FreeITSM needs PHP 8.1 (2026-10-25). includes/php_version_guard.php stops an
 * older PHP with a plain message, from the top of includes/ssl.php (config.php)
 * and includes/functions.php. This runs the REAL old PHP - the development
 * machine's oldest is 8.0, which is how 8.x syntax crept in unnoticed - so it
 * needs Docker.
 *
 * Checks:
 *   1. the guard file itself parses on PHP 7.4 (it must stay old-PHP syntax);
 *   2. PHP 7.4, from the command line, through functions.php: the message, exit 1;
 *   3. PHP 7.4 serving pages (setup, a page, an API endpoint, the portal): HTTP 500
 *      and the message - never "Parse error";
 *   4. CONTROL: PHP 8.4 serving the same pages never shows the message, so the
 *      guard is not simply refusing everything.
 *
 * Reads the repository read-only inside throwaway containers. No database.
 */

$root = str_replace('\\', '/', dirname(__DIR__));
$pass = 0; $fail = 0;
function check(string $label, bool $ok, string $detail = ''): void {
    global $pass, $fail;
    if ($ok) { $pass++; echo "  ok    $label\n"; }
    else     { $fail++; echo "  FAIL  $label" . ($detail !== '' ? "  - $detail" : '') . "\n"; }
}

/** Run a shell command in a throwaway php image with the repo mounted read-only. */
function inPhp(string $image, string $cmd, array $extraArgs = []): array {
    global $root;
    // The repo's config.php points at a Windows path; mount the Docker one over it.
    $args = array_merge(['docker', 'run', '--rm',
        '-v', "$root:/app:ro",
        '-v', "$root/docker/config.php:/app/config.php:ro",
        '-v', "$root/docker/db_config.php:/var/www/html/db_config.php:ro"], $extraArgs, [$image, 'sh', '-c', $cmd]);
    $line = implode(' ', array_map('escapeshellarg', $args)) . ' 2>&1';
    exec($line, $out, $code);
    return [implode("\n", $out), $code];
}

echo "Old PHP gets a sentence, not a parse error (PHP floor 8.1)\n";
echo str_repeat('=', 72) . "\n";

// ---------------------------------------------------------------- 0 (no Docker)
// A PAGE whose first require is an includes/ file has that file read BEFORE the guard
// can run - so 8.x syntax in it (includes/ may use 8.x) shows an old PHP a parse error.
// Such a page must require the guard first. Found 2026-10-25: setup (includes/i18n.php
// first), the self-service portal (its includes/header.php) and the debug-tool pages.
// API endpoints and scripts/ are not pages a person opens and are left out.
echo "\nPages load the guard before any includes/ file\n";
$late = []; $pagesSeen = 0;
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
foreach ($it as $f) {
    if ($f->getExtension() !== 'php') continue;
    $rel = substr(str_replace('\\', '/', $f->getPathname()), strlen($root) + 1);
    if (preg_match('#(^|/)(includes|tests|vendor|local|node_modules|\.git|scripts|docker)/#', $rel)
        || preg_match('#^api/|/manifest\.php$|^config\.php$|(^|/)_[^/]*\.php$#', $rel)) continue;   // _x.php = a partial, never opened
    $pagesSeen++;
    $tokens = token_get_all(file_get_contents($f->getPathname()));
    foreach ($tokens as $i => $tk) {
        if (!is_array($tk) || !in_array($tk[0], [T_REQUIRE, T_REQUIRE_ONCE, T_INCLUDE, T_INCLUDE_ONCE], true)) continue;
        $expr = ''; for ($k = $i + 1; isset($tokens[$k]) && $tokens[$k] !== ';'; $k++) $expr .= is_array($tokens[$k]) ? $tokens[$k][1] : $tokens[$k];
        if (preg_match('#includes/#', $expr) && !preg_match('#config\.php|includes/(functions|ssl|php_version_guard)\.php#', $expr)) {
            $late[] = "$rel: first loads " . trim(preg_replace('/\s+/', ' ', $expr));
        }
        break;
    }
}
check("CONTROL: pages were examined ($pagesSeen - more than 300)", $pagesSeen > 300);
check('no page loads an includes/ file before the guard', !$late, implode("\n          ", $late));

exec('docker version --format "{{.Server.Version}}" 2>&1', $v, $dc);
if ($dc !== 0) { echo "  SKIP  Docker is not running - the rest needs it\n"; exit($fail ? 1 : 2); }

// ---------------------------------------------------------------- 1
echo "\nThe guard file\n";
[$out, $code] = inPhp('php:7.4-cli', 'php -l /app/includes/php_version_guard.php');
check('parses on PHP 7.4', $code === 0, $out);
// And the two files that load it first - PHP parses a whole file before running it,
// so PHP 8 syntax in either would beat the guard. Loading an enum file with require is fine.
foreach (['functions.php', 'ssl.php'] as $carrier) {
    [$out, $code] = inPhp('php:7.4-cli', "php -l /app/includes/$carrier");
    check("includes/$carrier (loads it first) parses on PHP 7.4", $code === 0, $out);
}

// ---------------------------------------------------------------- 2
// TRAP (this harness): exec() goes through cmd.exe on Windows, which eats % and
// nested double quotes - so no php -r "...", no curl -w '%{http_code}'.
echo "\nPHP 7.4 from the command line\n";
[$out, $code] = inPhp('php:7.4-cli', 'php /app/includes/functions.php');
check('functions.php: exits 1', $code === 1, "exit $code");
check('functions.php: says PHP 8.1 is needed', str_contains($out, 'FreeITSM needs PHP 8.1 or newer'), $out);
check('functions.php: no parse error', $out !== '' && stripos($out, 'Parse error') === false, $out);

// ---------------------------------------------------------------- 3 + 4
$pages = ['/setup/', '/self-service/', '/self-service/tickets.php', '/system/debug-tools/d005/', '/lms/native-player.php',
          '/system/help/analysts.php', '/system/analysts/', '/api/tickets/get_analysts.php',
          '/api/self-service/submit_catalogue_form.php', '/api/rfp-builder/upload_document.php', '/index.php'];
$probe = 'php -S 127.0.0.1:8099 -t /app >/dev/null 2>&1 & sleep 2; for p in ' . implode(' ', $pages)
       . '; do echo === $p; curl -s -i http://127.0.0.1:8099$p | head -c 900; echo; done';
$blockFor = function (string $out, string $p): string {
    return preg_match('/=== ' . preg_quote($p, '/') . '\n(.*?)(?==== \/|\z)/s', $out, $m) ? $m[1] : '';
};

echo "\nPHP 7.4 serving pages\n";
[$out, $code] = inPhp('php:7.4-cli', 'apt-get -qq update >/dev/null 2>&1; apt-get -qq install -y curl >/dev/null 2>&1; ' . $probe);
foreach ($pages as $p) {
    $block = $blockFor($out, $p);
    check("$p: HTTP 500 with the message", preg_match('#^HTTP/1\.\d 500#', $block) === 1 && str_contains($block, 'FreeITSM needs PHP 8.1'), trim(substr($block, 0, 200)));
    check("$p: no parse error", $block !== '' && stripos($block, 'Parse error') === false);
}

echo "\nCONTROL: PHP 8.4 serving the same pages\n";
[$out, $code] = inPhp('php:8.4-cli', 'apt-get -qq update >/dev/null 2>&1; apt-get -qq install -y curl >/dev/null 2>&1; ' . $probe);
foreach ($pages as $p) {
    $block = $blockFor($out, $p);
    check("$p: answered, and without the message", preg_match('#^HTTP/1\.\d#', $block) === 1 && !str_contains($block, 'FreeITSM needs PHP'), trim(substr($block, 0, 200)));
}

// ---------------------------------------------------------------- 5
// The rule that makes 3 hold for every page, not just the ones above: a file the
// web server or cron runs directly must PARSE on old PHP, because PHP reads a whole
// file before running any of it - so 8.x syntax in the file itself beats the guard.
// 8.x syntax belongs in includes/ (any folder of that name), loaded after the guard.
echo "\nEvery file that is run directly parses on PHP 7.4\n";
[$out, $code] = inPhp('php:7.4-cli', 'set -f; find /app -name *.php -not -path */includes/* -not -path /app/tests/* -not -path */vendor/* -not -path */local/* -not -path */node_modules/* | wc -l; '
    . 'find /app -name *.php -not -path */includes/* -not -path /app/tests/* -not -path */vendor/* -not -path */local/* -not -path */node_modules/* -print0 | xargs -0 -n1 -P8 sh -c php\ -l\ $0\ 2\>\&1\ \|\|\ true | grep -E Parse.error');
$lines = array_values(array_filter(explode("\n", trim($out))));
$count = (int)array_shift($lines);
check("checked $count files (CONTROL: more than 1000)", $count > 1000, "only $count");
check('none gives a parse error', !$lines, implode("\n          ", $lines));

echo "\n$pass passed, $fail failed\n";
exit($fail ? 1 : 0);

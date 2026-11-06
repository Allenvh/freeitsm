<?php
/**
 * Build or refresh the Engineering wiki's Code map (engineering-wiki/code/).
 *
 *   php scripts/engineering-wiki-scan.php           only what changed since the last scan
 *   php scripts/engineering-wiki-scan.php --full    forget the last scan and read every file again
 *
 * Run it before a release so the shipped Code map matches the code. The same scan
 * runs from System -> Engineering wiki -> Rescan, in small steps.
 *
 * @see includes/engineering_wiki/scanner.php
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once __DIR__ . '/../includes/engineering_wiki/scanner.php';

$full = in_array('--full', $argv, true);
if ($full) {
    foreach (['manifest.json', 'state.json'] as $f) @unlink(ewRoot() . '/code/_scan/' . $f);
}

$t0 = microtime(true);
$scanner = new EwScanner();
$last = '';
$r = $scanner->runAll(function (array $r) use (&$last) {
    $line = sprintf("%-6s %d/%d files", $r['phase'], $r['done_files'], $r['total_files']);
    if ($line !== $last) { fwrite(STDOUT, $line . "\n"); $last = $line; }
});
if ($r['phase'] === 'busy') { fwrite(STDERR, "Another scan is running.\n"); exit(1); }
$info = EwScanner::lastScan();
printf("Done in %.1fs: %d files read, %d changed, %d pages (%d written).\n",
    microtime(true) - $t0, $info['files'] ?? 0, $info['changed_files'] ?? 0, $info['pages'] ?? 0, $info['written'] ?? 0);

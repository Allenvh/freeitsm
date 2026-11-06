<?php
/**
 * Stop on a PHP that is too old - with a sentence, not a parse error.
 *
 * FreeITSM needs PHP 8.1 (decided 2026-10-25). Files elsewhere use 8.0/8.1
 * syntax - match, named arguments, constructor promotion - which an older PHP
 * cannot even PARSE, so the person would otherwise get a white page and a
 * "Parse error" in a log they may not be able to read. This runs first instead,
 * from the top of includes/ssl.php (loaded by config.php) and includes/functions.php,
 * so it fires before any of that code is read.
 *
 * TRAP: this file must stay readable by very old PHP. No syntax newer than PHP
 * 5.3: no short arrays, no ?? or ?:, no typed anything, no arrow functions. It is
 * the one file a 7.4 (or older) server reads in full, and a syntax error here
 * brings back the white page this exists to prevent. tests/php-version-guard.php
 * checks it on real PHP 7.4 in Docker.
 *
 * Why not config.php: it is the operator's file and survives upgrades, so a check
 * added there never reaches existing installs (GH #129).
 *
 * @wiki Upgrading-PHP
 */

if (!defined('FREEITSM_MIN_PHP_ID')) {
    define('FREEITSM_MIN_PHP_ID', 80100);
    define('FREEITSM_MIN_PHP', '8.1');
}

if (!defined('PHP_VERSION_ID') || PHP_VERSION_ID < FREEITSM_MIN_PHP_ID) {
    $freeitsmPhpMessage = 'FreeITSM needs PHP ' . FREEITSM_MIN_PHP . ' or newer. This server is running PHP ' . PHP_VERSION . ".\n\n"
        . "PHP 8.3 or 8.4 is recommended. Nothing in FreeITSM or its database needs changing - upgrade PHP, restart the web server, and reload this page.\n\n"
        . "How to upgrade PHP: https://github.com/edmozley/freeitsm/wiki/Upgrading-PHP\n";
    if (PHP_SAPI === 'cli') {
        fwrite(STDERR, $freeitsmPhpMessage);
        exit(1);
    }
    if (!headers_sent()) {
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: text/plain; charset=utf-8');
    }
    echo $freeitsmPhpMessage;
    exit;
}

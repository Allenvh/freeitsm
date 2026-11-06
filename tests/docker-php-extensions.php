<?php
/* 🔴 NEVER OVER THE WEB. See tests/web-exposure-guard.php. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Every PHP extension the code uses must be in the official Docker image.
 *
 *   php tests/docker-php-extensions.php
 *
 * 🔴 GH #207. The image installed only pdo_mysql, so LDAP sign-in, SCORM course
 * upload and .docx reading all depended on extensions the container did not
 * have - and nothing noticed, because the developer's own PHP had them.
 *
 * docker/php-extensions.txt is the list the Dockerfile installs (and refuses to
 * build without). This test scans the code for each optional extension's
 * functions and classes, and fails when the code uses one that the list neither
 * installs nor names under "NOT IN THE IMAGE" with a reason.
 *
 * The other half - that the listed extensions really load - is checked inside
 * the image itself, by the Dockerfile's smoke-check step.
 *
 * Reads files only: no database, no web server, no Docker.
 */

$root = dirname(__DIR__);

$pass = 0; $fail = 0;
function check(string $label, bool $ok, string $detail = ''): void {
    global $pass, $fail;
    if ($ok) { $pass++; echo "  ok    $label\n"; }
    else     { $fail++; echo "  FAIL  $label" . ($detail !== '' ? "  - $detail" : '') . "\n"; }
}

echo "Extensions the code uses must be in the Docker image (GH #207)\n";
echo str_repeat('=', 72) . "\n";

// Extensions php:8.4-apache does NOT build in, and the code that means one is
// in use. Extensions the base image already has (curl, mbstring, openssl,
// sodium, dom, simplexml, fileinfo...) need no entry. Add a row when the code
// starts using another one.
const OPTIONAL_EXTENSIONS = [
    'ldap'      => '/\bldap_[a-z_]+\s*\(/',
    'zip'       => '/\bZipArchive\b/',
    'intl'      => '/\b(idn_to_ascii|idn_to_utf8|IntlDateFormatter|NumberFormatter|Collator|Normalizer::|transliterator_)/',
    'imap'      => '/\bimap_[a-z_]+\s*\(/',
    'gd'        => '/\b(imagecreate[a-z]*|imagepng|imagejpeg|imagecopyresampled)\s*\(/',
    'bcmath'    => '/\bbc(add|sub|mul|div|mod|pow)\s*\(/',
    'exif'      => '/\bexif_read_data\s*\(/',
    'soap'      => '/\bSoapClient\b/',
    'sockets'   => '/\bsocket_create\s*\(/',
    'gmp'       => '/\bgmp_[a-z]+\s*\(/',
    'pdo_pgsql' => '/pgsql:/',
];

/**
 * Read docker/php-extensions.txt.
 *
 * @return array  [installed => [ext...], excluded => [ext...]]
 */
function readExtensionList(string $path): array {
    $installed = []; $excluded = [];
    $inExcluded = false;
    foreach (file($path) as $line) {
        // The heading line exactly - the header's prose mentions the phrase too.
        if (preg_match('/^#\s*NOT IN THE IMAGE\b/', $line)) { $inExcluded = true; continue; }
        if ($inExcluded) {
            // "# imap      reason..." - the first word after the hash, at the start of an entry
            if (preg_match('/^#\s([a-z_0-9]+)\s{2,}\S/', $line, $m)) $excluded[] = $m[1];
            continue;
        }
        $ext = trim(preg_replace('/#.*/', '', $line));
        if ($ext !== '') $installed[] = $ext;
    }
    return ['installed' => $installed, 'excluded' => $excluded];
}

// ---------------------------------------------------------------- 1. the reader
echo "\nThe list reader\n";
$tmp = tempnam(sys_get_temp_dir(), 'ext');
file_put_contents($tmp, "# header\npdo_mysql  # db\nzip\n\n# NOT IN THE IMAGE\n# imap      because\n#           more reason\n");
$r = readExtensionList($tmp);
unlink($tmp);
check('installed entries are read, comments stripped', $r['installed'] === ['pdo_mysql', 'zip'], json_encode($r['installed']));
check('excluded entries are read, continuation lines are not', $r['excluded'] === ['imap'], json_encode($r['excluded']));

$list = readExtensionList($root . '/docker/php-extensions.txt');
check('the real list installs pdo_mysql', in_array('pdo_mysql', $list['installed'], true));

// ---------------------------------------------------------------- 2. the Dockerfile installs the list
echo "\nThe Dockerfile\n";
$dockerfile = file_get_contents($root . '/Dockerfile');
preg_match('/docker-php-ext-install\b[^\n]*/', $dockerfile, $m);
$installLine = $m[0] ?? '';
foreach ($list['installed'] as $ext) {
    // PHP 8.4 moved IMAP to PECL; the fork builds that pinned extension separately.
    $peclImap = $ext === 'imap' && str_contains($dockerfile, 'https://pecl.php.net/get/imap-1.0.3.tgz')
        && str_contains($dockerfile, './configure --with-imap --with-kerberos --with-imap-ssl')
        && str_contains($dockerfile, 'docker-php-ext-enable imap');
    check("Dockerfile installs $ext", $peclImap || (bool)preg_match('/\s' . preg_quote($ext, '/') . '(\s|;|$)/', $installLine), $installLine);
}
check('the smoke check reads the list', str_contains($dockerfile, 'freeitsm-php-extensions.txt')
      && str_contains($dockerfile, 'COPY docker/php-extensions.txt'));

// ---------------------------------------------------------------- 3. what the code uses
echo "\nWhat the code uses\n";
$skipDirs = ['/.git/', '/tests/', '/vendor/', '/node_modules/', '/samples/', '/local/'];
$used = [];   // ext => first file
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
foreach ($it as $file) {
    if ($file->getExtension() !== 'php') continue;
    $path = str_replace('\\', '/', $file->getPathname());
    foreach ($skipDirs as $d) { if (str_contains($path, $d)) continue 2; }
    $src = file_get_contents($path);
    foreach (OPTIONAL_EXTENSIONS as $ext => $re) {
        if (!isset($used[$ext]) && preg_match($re, $src)) $used[$ext] = substr($path, strlen(str_replace('\\', '/', $root)) + 1);
    }
}
ksort($used);
foreach ($used as $ext => $where) {
    $ok = in_array($ext, $list['installed'], true) || in_array($ext, $list['excluded'], true);
    check("$ext (used in $where) is installed or excluded with a reason", $ok,
          'add it to docker/php-extensions.txt and the Dockerfile, or explain under NOT IN THE IMAGE');
}
// Control: the scan must actually find the ones we know about, or a broken
// pattern would pass everything.
check('CONTROL: the scan finds ldap', isset($used['ldap']));
check('CONTROL: the scan finds zip', isset($used['zip']));

echo "\n$pass passed, $fail failed\n";
exit($fail ? 1 : 0);

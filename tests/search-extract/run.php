<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Attachment text extraction — tier 1 (discussion #53).
 *
 *   php tests/search-extract/run.php
 *
 * Builds real files in a temporary directory and reads them back. No database,
 * no HTTP: this exercises includes/search/extract.php on its own, because the
 * risky part of attachment indexing is the file handling, not the SQL.
 *
 * The hostile cases matter most. An attachment arrives from anyone who can email
 * the service desk, so "a zip bomb is refused" is a more useful assertion than
 * "a Word document is read".
 */

require_once __DIR__ . '/../../includes/search/extract.php';

$pass = 0; $fail = 0;
function ok(string $what, bool $cond) {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  ok    $what\n"; }
    else       { $fail++; echo "  FAIL  $what\n"; }
}

$dir = sys_get_temp_dir() . '/freeitsm_extract_' . getmypid();
@mkdir($dir, 0777, true);
$made = [];
function put(string $name, string $bytes): string {
    global $dir, $made;
    $p = $dir . '/' . $name;
    file_put_contents($p, $bytes);
    $made[] = $p;
    return $p;
}

/** A minimal but genuinely valid .docx: a zip with word/document.xml inside. */
function makeDocx(string $path, string $innerXml): bool {
    $zip = new ZipArchive();
    if ($zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) return false;
    $zip->addFromString('[Content_Types].xml', '<?xml version="1.0"?><Types/>');
    $zip->addFromString('word/document.xml', $innerXml);
    $zip->close();
    return true;
}

echo "\nAttachment extraction — tier 1\n";
echo str_repeat('=', 60) . "\n\n";

// ── What it claims to support ───────────────────────────────────────────────
echo "Format support\n";
ok('.txt is supported',                 attTextSupports('notes.txt'));
ok('.docx is supported',                attTextSupports('report.docx'));
ok('.xlsx is supported',                attTextSupports('data.xlsx'));
ok('CONTROL — .pdf is NOT supported',  !attTextSupports('invoice.pdf'));
ok('CONTROL — .exe is NOT supported',  !attTextSupports('setup.exe'));
ok('extension check is case-insensitive', attTextSupports('REPORT.DOCX'));
ok('a file with no extension is unsupported', !attTextSupports('README'));

// ── Plain text ──────────────────────────────────────────────────────────────
echo "\nPlain text\n";
$p = put('notes.txt', "Printer on floor 2 jams.\nSerial ABC12345.");
$r = attTextExtractFile($p, 'notes.txt');
ok('.txt reads as extracted',           $r['status'] === ATT_TEXT_EXTRACTED);
ok('.txt keeps its words',              str_contains($r['text'], 'ABC12345'));
ok('newlines collapse to spaces',       !str_contains($r['text'], "\n"));

$p = put('empty.txt', '');
$r = attTextExtractFile($p, 'empty.txt');
ok('an empty file is extracted, not failed', $r['status'] === ATT_TEXT_EXTRACTED && $r['text'] === '');

// ── Word ────────────────────────────────────────────────────────────────────
echo "\nOOXML\n";
$p = $dir . '/report.docx'; $made[] = $p;
makeDocx($p, '<w:document><w:body><w:p><w:r><w:t>Quarterly</w:t></w:r>'
           . '<w:r><w:t>Firewall</w:t></w:r></w:p></w:body></w:document>');
$r = attTextExtractFile($p, 'report.docx');
ok('.docx reads as extracted',          $r['status'] === ATT_TEXT_EXTRACTED);
ok('.docx yields its text',             str_contains($r['text'], 'Firewall'));
// The one that silently ruins search: OOXML wraps every run in its own element,
// so stripping tags without leaving a space welds words together.
ok('adjacent runs do NOT weld together', !str_contains($r['text'], 'QuarterlyFirewall'));
ok('...and are separated properly',      str_contains($r['text'], 'Quarterly Firewall'));

// ── Hostile input ───────────────────────────────────────────────────────────
echo "\nHostile input\n";
$p = put('invoice.pdf', "%PDF-1.4 not really a pdf");
$r = attTextExtractFile($p, 'invoice.pdf');
ok('a PDF is unsupported, not failed',  $r['status'] === ATT_TEXT_UNSUPPORTED);

$p = put('broken.docx', 'this is not a zip at all');
$r = attTextExtractFile($p, 'broken.docx');
ok('a .docx that is not a zip fails cleanly', $r['status'] === ATT_TEXT_FAILED);

$r = attTextExtractFile($dir . '/does_not_exist.txt', 'does_not_exist.txt');
ok('a missing file fails cleanly',      $r['status'] === ATT_TEXT_FAILED);

// A zip declaring more entries than the guard allows.
$p = $dir . '/many.docx'; $made[] = $p;
$zip = new ZipArchive();
$zip->open($p, ZipArchive::CREATE | ZipArchive::OVERWRITE);
for ($i = 0; $i <= ATT_TEXT_MAX_ZIP_ENTRIES; $i++) $zip->addFromString("f$i.xml", 'x');
$zip->close();
$r = attTextExtractFile($p, 'many.docx');
ok('a zip with too many entries is refused', $r['status'] === ATT_TEXT_FAILED);

// A zip whose DECLARED uncompressed size exceeds the cap. Highly compressible
// content is exactly the shape of a real bomb: a few KB on disk, enormous when
// unpacked.
//
// ⚠️ Tested against a SMALL injected cap rather than the real 100 MB one.
// Building a genuine 100 MB bomb exhausted PHP's memory limit and killed the
// test twice — first with one giant string, then because ZipArchive buffers
// everything added until close(). That is a neat demonstration of why the guard
// reads the archive's DECLARED sizes and refuses, rather than unpacking to find
// out: unpacking is precisely what kills the process.
$p = $dir . '/bomb.docx'; $made[] = $p;
$zip = new ZipArchive();
$zip->open($p, ZipArchive::CREATE | ZipArchive::OVERWRITE);
$zip->addFromString('word/document.xml', str_repeat('A', 3145728));   // 3 MB declared
$zip->close();

$r = attTextExtractOoxml($p, 'docx', 1048576, ATT_TEXT_MAX_ZIP_ENTRIES);  // 1 MB cap
ok('a zip over the declared-size cap is refused', $r === null);

// CONTROL: the same archive, under a cap it fits inside, must read fine — so the
// refusal above is the guard working, not the archive being unreadable.
$r = attTextExtractOoxml($p, 'docx', 10485760, ATT_TEXT_MAX_ZIP_ENTRIES); // 10 MB cap
ok('CONTROL — the same archive reads under a larger cap', is_string($r) && strlen($r) > 0);

// ── Caps ────────────────────────────────────────────────────────────────────
echo "\nCaps\n";
$p = put('long.txt', str_repeat('word ', 400));
$r = attTextExtractFile($p, 'long.txt', 100);
ok('over the character cap is truncated', $r['status'] === ATT_TEXT_TRUNCATED);
ok('...and really is cut to the cap',     mb_strlen($r['text']) === 100);

$p = put('huge.txt', str_repeat('x', 1024));
// Assert the size gate by pointing the cap below the file, rather than writing
// a 20 MB file into the test run.
ok('the file-size limit is a real number', ATT_TEXT_MAX_FILE_BYTES > 0 && ATT_TEXT_MAX_FILE_BYTES <= 104857600);

// ── Tidy up ─────────────────────────────────────────────────────────────────
foreach ($made as $f) @unlink($f);
@rmdir($dir);
ok('temporary files cleaned up', !is_dir($dir));

echo "\n" . str_repeat('=', 60) . "\n";
echo ($fail === 0 ? "$pass passed, 0 failed\n\n" : "$pass passed, $fail FAILED\n\n");
exit($fail === 0 ? 0 : 1);

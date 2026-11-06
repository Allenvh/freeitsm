<?php
/**
 * Pull readable text out of an attachment — the dependency-free tier
 * (discussion #53, tier 1 of the wiki's §8.2).
 *
 * WHAT THIS DELIBERATELY DOES NOT DO
 * ----------------------------------
 * No PDFs, no legacy .doc/.xls, no OCR. Those need a real parser, and a real
 * parser has no business running inside the web request:
 *
 *   - The input is HOSTILE. An attachment arrives from anyone who can email the
 *     service desk, through api/tickets/check_mailbox_email.php, which has no
 *     authentication at all. Malformed documents are a well-trodden route to
 *     remote code execution, and this process holds the database credentials.
 *   - FreeITSM has no Composer, so a PHP PDF library would be vendored by hand
 *     and ours to patch for its lifetime. A bundled library version was itself
 *     a finding in the August 2026 security review.
 *
 * Those formats are tier 2: one external extractor, in a container, reached over
 * HTTP — see includes/search/tika.php. Until an install configures one they are
 * recorded as `unsupported`, which is
 * the honest answer rather than silence.
 *
 * WHAT IT DOES HANDLE, AND WHY IT NEEDS NOTHING
 * ---------------------------------------------
 *   .txt .csv .log .md .json .xml   already text; read it
 *   .docx .xlsx .pptx               a ZIP of XML. ZipArchive + strip tags.
 *                                   includes/rfp_docx_parser.php has done this
 *                                   since the RFP builder; no library involved.
 *
 * ⚠️ The RFP parser is NOT reused as-is. It was written for documents chosen and
 * uploaded by a signed-in member of staff; these arrive from strangers. Same
 * file format, completely different threat model — hence the zip-bomb guards
 * below, which that parser has no reason to carry.
 */

/** Statuses. Stored in attachment_text.status and shown in the UI. */
const ATT_TEXT_EXTRACTED   = 'extracted';
const ATT_TEXT_TRUNCATED   = 'truncated';
const ATT_TEXT_TOO_LARGE   = 'too_large';
const ATT_TEXT_UNSUPPORTED = 'unsupported';
const ATT_TEXT_FAILED      = 'failed';
/** Queued for an extractor that is configured but was not reachable. RETRIED. */
const ATT_TEXT_PENDING     = 'pending';

/**
 * CLAIMED by a worker that is reading it right now.
 *
 * ⚠️ Transient, and never a resting state. Two workers — a cron run and an
 * analyst opening a page — would otherwise both select the same oldest pending
 * rows and send the same files to the extractor independently, which for OCR
 * means paying two or three times for one answer.
 *
 * A worker that dies mid-file leaves its rows here, so anything older than
 * ATT_TEXT_CLAIM_STALE_MINUTES is returned to `pending` by the next drain.
 * A status nothing will ever act on is a leak.
 */
const ATT_TEXT_EXTRACTING  = 'extracting';

/** How long a claim may sit before it is assumed abandoned. */
const ATT_TEXT_CLAIM_STALE_MINUTES = 15;

/** Files bigger than this are never opened. */
const ATT_TEXT_MAX_FILE_BYTES = 20971520;   // 20 MB

/** Most text kept from one attachment. */
const ATT_TEXT_MAX_CHARS = 200000;

/**
 * ⚠️ Zip-bomb guards. A 40 KB .docx can declare terabytes of uncompressed XML,
 * and ZipArchive will happily hand it over one entry at a time until the process
 * dies. Both limits are on the DECLARED uncompressed size, checked before
 * anything is read.
 */
const ATT_TEXT_MAX_UNZIPPED_BYTES = 104857600;   // 100 MB total across entries
const ATT_TEXT_MAX_ZIP_ENTRIES    = 2000;

/** Extensions read straight off disk as text. */
const ATT_TEXT_PLAIN_EXT = ['txt', 'csv', 'log', 'md', 'json', 'xml', 'yml', 'yaml', 'ini'];

/** Office formats that are a zip of XML. Value = the entries worth reading. */
const ATT_TEXT_OOXML = [
    'docx' => ['word/document.xml'],
    'xlsx' => ['xl/sharedStrings.xml'],           // cell text lives here, once each
    'pptx' => ['ppt/slides/'],                    // prefix: every slide
];

/**
 * The extension, lowercased, or '' if there isn't one.
 *
 * @param string $filename  the file name
 */
function attTextExtension(string $filename): string {
    $dot = strrpos($filename, '.');
    return $dot === false ? '' : strtolower(substr($filename, $dot + 1));
}

/**
 * Could this tier read the file at all? Used to answer before opening anything.
 *
 * @param string $filename  the file name - only its extension is judged
 */
function attTextSupports(string $filename): bool {
    $ext = attTextExtension($filename);
    return in_array($ext, ATT_TEXT_PLAIN_EXT, true) || isset(ATT_TEXT_OOXML[$ext]);
}

/**
 * Reduce extracted XML/text to the words worth indexing.
 *
 * OOXML puts every run of text in its own element, so stripping tags without
 * putting a space in their place welds words together — "Please" + "reboot"
 * becomes "Pleasereboot" and is then unfindable by either.
 *
 * @param string $raw  the text or XML read from the file
 */
function attTextNormalise(string $raw): string {
    $s = preg_replace('/<[^>]*>/', ' ', $raw);
    $s = html_entity_decode($s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    // Control characters, minus tab/newline, would otherwise reach the index.
    $s = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/u', ' ', $s) ?? $s;
    $s = preg_replace('/\s+/u', ' ', $s) ?? $s;
    return trim($s);
}

/**
 * Read the text of one file.
 *
 * Never throws: an attachment that cannot be read is a status, not an error, and
 * must not take down the indexing of the ticket it belongs to.
 *
 * @param string $path  the file's path on disk, already checked to be inside the uploads directory
 * @param string $filename  the file's original name - its extension picks the reader
 * @param int $maxChars  the most characters to keep; longer text is cut and marked truncated
 * @return array{status:string,text:string}
 */
function attTextExtractFile(string $path, string $filename, int $maxChars = ATT_TEXT_MAX_CHARS): array {
    $fail = fn(string $s) => ['status' => $s, 'text' => ''];

    if (!attTextSupports($filename))            return $fail(ATT_TEXT_UNSUPPORTED);
    if (!is_file($path) || !is_readable($path)) return $fail(ATT_TEXT_FAILED);

    $size = @filesize($path);
    if ($size === false)                         return $fail(ATT_TEXT_FAILED);
    if ($size > ATT_TEXT_MAX_FILE_BYTES)         return $fail(ATT_TEXT_TOO_LARGE);

    $ext = attTextExtension($filename);

    try {
        if (in_array($ext, ATT_TEXT_PLAIN_EXT, true)) {
            // Read a bounded amount rather than the whole file: the cap is on
            // characters kept, so there is no reason to pull 20 MB into memory
            // to throw nearly all of it away. 4x for multi-byte headroom.
            $raw = @file_get_contents($path, false, null, 0, $maxChars * 4);
            if ($raw === false) return $fail(ATT_TEXT_FAILED);
            if (!mb_check_encoding($raw, 'UTF-8')) {
                $raw = @mb_convert_encoding($raw, 'UTF-8', 'UTF-8, Windows-1252, ISO-8859-1');
            }
            $text = attTextNormalise((string)$raw);
        } else {
            $text = attTextExtractOoxml($path, $ext);
            if ($text === null) return $fail(ATT_TEXT_FAILED);
        }
    } catch (Throwable $e) {
        error_log('[attTextExtractFile] ' . $filename . ': ' . $e->getMessage());
        return $fail(ATT_TEXT_FAILED);
    }

    if ($text === '') {
        // Read fine, nothing in it. Not a failure — an image-only PDF would be
        // the same, and tier 2 will have more to say about those.
        return ['status' => ATT_TEXT_EXTRACTED, 'text' => ''];
    }

    if (mb_strlen($text, 'UTF-8') > $maxChars) {
        return ['status' => ATT_TEXT_TRUNCATED, 'text' => mb_substr($text, 0, $maxChars, 'UTF-8')];
    }
    return ['status' => ATT_TEXT_EXTRACTED, 'text' => $text];
}

/**
 * Text out of a docx/xlsx/pptx. Returns null if the archive cannot be opened.
 *
 * ⚠️ Every guard here is because the file came from a stranger. The limits are
 * checked against the archive's DECLARED sizes before a single entry is read, so
 * a bomb is refused rather than survived.
 *
 * @param string $path  the .docx, .xlsx or .pptx file's path on disk
 * @param string $ext  its extension, lower case: 'docx', 'xlsx' or 'pptx'
 * @param int $maxUnzipped  the most bytes the archive may declare once unzipped; more is refused
 * @param int $maxEntries  the most entries the archive may hold; more is refused
 */
function attTextExtractOoxml(
    string $path,
    string $ext,
    int $maxUnzipped = ATT_TEXT_MAX_UNZIPPED_BYTES,
    int $maxEntries  = ATT_TEXT_MAX_ZIP_ENTRIES
): ?string {
    if (!class_exists('ZipArchive')) return null;   // ext-zip absent; not our failure to hide

    $zip = new ZipArchive();
    if ($zip->open($path) !== true) return null;

    try {
        if ($zip->numFiles > $maxEntries) return null;

        $declared = 0;
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $stat = $zip->statIndex($i);
            if ($stat === false) continue;
            $declared += (int)$stat['size'];
            if ($declared > $maxUnzipped) return null;
        }

        $wanted = ATT_TEXT_OOXML[$ext] ?? [];
        $parts  = [];
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);
            if ($name === false) continue;
            foreach ($wanted as $w) {
                // A trailing slash means "every entry under here" (pptx slides).
                $hit = substr($w, -1) === '/' ? str_starts_with($name, $w) : $name === $w;
                if (!$hit) continue;
                $xml = $zip->getFromIndex($i);
                if ($xml !== false) $parts[] = $xml;
                break;
            }
        }
        return attTextNormalise(implode(' ', $parts));
    } finally {
        $zip->close();
    }
}

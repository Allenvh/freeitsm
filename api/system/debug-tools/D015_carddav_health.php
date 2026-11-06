<?php
/**
 * Debug Tool D015 — Is the CardDAV connection healthy, in both directions?
 *
 * D011 asks whether a directory is CONFIGURED to import. This asks whether an
 * address book actually WORKS right now — and, unlike D011, it does contact the
 * server, because "can we reach it" is the whole question here rather than a
 * distraction from it.
 *
 * 🔑 It walks the same path a real write takes, in order: reach the server,
 * authenticate, read the chosen book, and ask whether this account may write to
 * it. Each rung is reported separately, because they fail for entirely different
 * reasons and an operator told only "it does not work" has four things to check.
 *
 * 🔴 IT WRITES NOTHING. The write rung is a PROPFIND for
 * `current-user-privilege-set`, which asks the server what the account is
 * allowed to do. Proving it by creating a card and deleting it again is worse
 * than it sounds — the delete can fail, and the operator is left with a contact
 * called "FreeITSM test" in a real address book.
 *
 * Also reports what only FreeITSM's own side can answer: how many people this
 * book manages, how many are missing the card URL a write-back needs, and what
 * the write log has recorded lately.
 *
 * Output: plain text, section-delimited with === HEADERS ===.
 */

@session_start();

$DIAG_ID   = 'D015';
$DIAG_NAME = 'CardDAV health — can FreeITSM read and write this address book';

require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../includes/functions.php';

// Debug tools are administrators-only (issue #34). Fail closed.
try {
    $__dbgAdmin = !empty($_SESSION['analyst_id']) && analystIsAdmin(connectToDatabase(), (int)$_SESSION['analyst_id']);
} catch (Throwable $e) {
    $__dbgAdmin = false;
}
if (!$__dbgAdmin) {
    http_response_code(403);
    if (!headers_sent()) header('Content-Type: text/plain; charset=utf-8');
    echo "Administrator access required.\n";
    exit;
}

if (!headers_sent()) header('Content-Type: text/plain; charset=utf-8');

require_once __DIR__ . '/../../../includes/encryption.php';
require_once __DIR__ . '/../../../includes/carddav_write.php';

$sections = [];
/**
 * Adds one titled section ("=== TITLE ===" and its lines) to the report; $body is a
 * string or an array of lines.
 *
 * @param mixed $sections  the report so far, a list of section strings - appended to by reference
 * @param mixed $title  the section heading, printed between === marks
 * @param mixed $body  the section text - a string, or an array of lines
 */
function addSection(&$sections, $title, $body) {
    if (is_array($body)) $body = implode("\n", $body);
    $sections[] = "=== {$title} ===\n" . rtrim($body, "\n");
}
/**
 * Formats a boolean as YES or NO for the report.
 *
 * @param mixed $v  any value, judged as true or false
 */
function yn($v) { return $v ? 'YES' : 'NO'; }
/**
 * Hide the host and the account, keep the shape. Reports get sent on.
 *
 * @param string $u  a CardDAV address; empty gives (not set)
 */
function maskUrl(string $u): string {
    $p = @parse_url($u);
    if (!$p || empty($p['host'])) return $u === '' ? '(not set)' : '(unreadable)';
    return ($p['scheme'] ?? 'http') . '://' . preg_replace('/[^.]/', '*', $p['host'])
         . (isset($p['port']) ? ':' . $p['port'] : '') . ($p['path'] ?? '');
}
/**
 * Masks a username to its first character followed by stars.
 *
 * @param string $u  a user name; empty gives (none)
 */
function maskUser(string $u): string {
    if ($u === '') return '(none)';
    return mb_substr($u, 0, 1) . str_repeat('*', max(1, mb_strlen($u) - 1));
}
/**
 * Prints every section, separated by blank lines, and stops - the end of the report, and
 * the early exit when a check cannot go on.
 *
 * @param mixed $sections  the report, a list of section strings as built by addSection()
 */
function emit_and_exit($sections) {
    echo implode("\n\n", $sections) . "\n";
    exit;
}

$conn = connectToDatabase();

// ---------------------------------------------------------------- prerequisites
$pre = [];
$pre[] = 'PHP curl extension           : ' . yn(function_exists('curl_init'))
       . (function_exists('curl_init') ? '' : '   <-- BLOCKER: no CardDAV at all without it');
$pre[] = 'includes/carddav.php         : ' . yn(file_exists(__DIR__ . '/../../../includes/carddav.php'));
$pre[] = 'includes/carddav_write.php   : ' . yn(file_exists(__DIR__ . '/../../../includes/carddav_write.php'))
       . '   (absent = this install predates write-back)';

$hasWriteCol = false; $hasRefCol = false; $hasLogTable = false;
try { $hasWriteCol = (bool)$conn->query("SHOW COLUMNS FROM auth_providers LIKE 'carddav_write_back'")->fetch(); } catch (Throwable $e) {}
try { $hasRefCol   = (bool)$conn->query("SHOW COLUMNS FROM user_sso_identities LIKE 'source_ref'")->fetch(); } catch (Throwable $e) {}
try { $hasLogTable = (bool)$conn->query("SHOW TABLES LIKE 'carddav_write_log'")->fetch(); } catch (Throwable $e) {}
$pre[] = 'auth_providers.carddav_write_back : ' . yn($hasWriteCol);
$pre[] = 'user_sso_identities.source_ref    : ' . yn($hasRefCol)
       . '   (write-back cannot locate a card without it)';
$pre[] = 'carddav_write_log table           : ' . yn($hasLogTable);
if (!$hasWriteCol || !$hasRefCol || !$hasLogTable) {
    $pre[] = '';
    $pre[] = 'FIX: run System > Database Verify. These are created automatically.';
}
addSection($sections, 'PREREQUISITES', $pre);

// ---------------------------------------------------------------- the providers
$providers = $conn->query("SELECT * FROM auth_providers WHERE protocol = 'carddav' ORDER BY id")
                  ->fetchAll(PDO::FETCH_ASSOC);

if (!$providers) {
    addSection($sections, 'ADDRESS BOOKS', [
        'None configured.',
        '',
        'There is no CardDAV address book on this install, so there is nothing to check.',
        'Add one at System > Authentication > Add, choosing CardDAV as the type.',
    ]);
    addSection($sections, 'SUMMARY', ['Nothing to test.']);
    emit_and_exit($sections);
}

foreach ($providers as $p) {
    $out = [];
    $pid = (int)$p['id'];
    $out[] = 'Name              : ' . $p['display_name'];
    $out[] = 'Enabled (imports) : ' . yn((int)$p['enabled'] === 1);
    $out[] = 'Server            : ' . maskUrl((string)$p['carddav_url']);
    $out[] = 'Account           : ' . maskUser((string)$p['carddav_username']);
    $out[] = 'Password stored   : ' . yn(!empty($p['carddav_password']));
    $out[] = 'Auth setting      : ' . ($p['carddav_auth'] ?: 'auto');
    $out[] = 'Address book      : ' . (($p['carddav_addressbook'] ?? '') !== '' ? $p['carddav_addressbook'] : '(NOT CHOSEN)');
    $out[] = 'Scope             : ' . ($p['carddav_scope'] ?: 'all');
    $out[] = 'Write changes back: ' . yn((int)($p['carddav_write_back'] ?? 0) === 1);
    $out[] = '';

    $cfg = cardDavConfigFromProvider($p);

    // --- rung 1 and 2: reach it, and sign in ---
    if (trim($cfg['url']) === '') {
        $out[] = 'BLOCKER: no server address is set. Nothing further can be tested.';
        addSection($sections, 'ADDRESS BOOK #' . $pid, $out);
        continue;
    }
    $books = cardDavListAddressBooks($cfg);
    $out[] = '1. Reach the server + sign in : ' . ($books['ok'] ? 'OK' : 'FAILED');
    $out[] = '   HTTP status                : ' . $books['status'];
    $out[] = '   Auth scheme the server offers: ' . ($books['auth'] ?: 'unknown');
    if (!$books['ok']) {
        $out[] = '   Reason                     : ' . $books['error'];
        $out[] = '';
        $out[] = 'BLOCKER: FreeITSM cannot talk to this server, so read and write are both';
        $out[] = 'untestable. ⚠️ A stock Baikal ships DIGEST authentication and answers Basic';
        $out[] = 'with a flat 401 that reads exactly like a wrong password — if the Auth';
        $out[] = 'setting above is not "auto", try setting it back.';
        addSection($sections, 'ADDRESS BOOK #' . $pid, $out);
        continue;
    }
    $out[] = '   Address books visible      : ' . count($books['books']);

    // --- rung 3: read the chosen book ---
    $book = trim((string)($p['carddav_addressbook'] ?? ''));
    if ($book === '') {
        $out[] = '';
        $out[] = '2. Read the chosen book       : SKIPPED - no book has been chosen yet.';
        $out[] = '   FIX: Configure > Contacts > Address book, then Save.';
        addSection($sections, 'ADDRESS BOOK #' . $pid, $out);
        continue;
    }
    $scan = cardDavScanBook($cfg, $book);
    $out[] = '';
    $out[] = '2. Read the chosen book       : ' . ($scan['ok'] ? 'OK' : 'FAILED');
    if ($scan['ok']) {
        $out[] = '   Contacts in the book       : ' . $scan['contacts'];
        $out[] = '   Groups / tags available    : ' . count($scan['groups']) . ' / ' . count($scan['categories']);
    } else {
        $out[] = '   Reason                     : ' . $scan['error'];
    }

    // --- rung 4: may we write? ---
    $perm = cardDavCanWrite($cfg, $book);
    $out[] = '';
    $out[] = '3. May this account WRITE?    : '
           . ($perm['known'] ? ($perm['writable'] ? 'YES' : 'NO') : 'UNKNOWN');
    if ($perm['known']) {
        $out[] = '   Privileges the server grants: ' . implode(', ', $perm['privileges']);
    } else {
        $out[] = '   Reason                     : ' . $perm['error'];
        $out[] = '   (unknown is not the same as no - some servers simply do not report it)';
    }
    $out[] = '   NOTE: nothing was written to find this out.';

    // --- the mismatch that actually bites ---
    $out[] = '';
    if ((int)($p['carddav_write_back'] ?? 0) === 1 && $perm['known'] && !$perm['writable']) {
        $out[] = '🔴 MISMATCH: write-back is switched ON but the account may not write.';
        $out[] = '   Every change an analyst makes will be saved in FreeITSM and refused by';
        $out[] = '   the server. Grant write access on the address book server, or switch';
        $out[] = '   write-back off so nobody is misled.';
    } elseif ((int)($p['carddav_write_back'] ?? 0) !== 1 && $perm['writable']) {
        $out[] = 'Note: this account COULD write, but write-back is switched off. That is a';
        $out[] = 'perfectly good setting - it just means changes stay in FreeITSM.';
    }

    // --- our own side ---
    $managed = 0; $noRef = 0; $noEtag = 0;
    try {
        $managed = (int)$conn->query("SELECT COUNT(*) FROM users WHERE is_managed=1 AND auth_provider_id=$pid")->fetchColumn();
        if ($hasRefCol) {
            $noRef  = (int)$conn->query("SELECT COUNT(*) FROM user_sso_identities WHERE provider_id=$pid AND (source_ref IS NULL OR source_ref='')")->fetchColumn();
            $noEtag = (int)$conn->query("SELECT COUNT(*) FROM user_sso_identities WHERE provider_id=$pid AND (source_etag IS NULL OR source_etag='')")->fetchColumn();
        }
    } catch (Throwable $e) {}
    $out[] = '';
    $out[] = '4. FreeITSM\'s own side';
    $out[] = '   People managed by this book: ' . $managed;
    $out[] = '   Missing a card URL         : ' . $noRef
           . ($noRef > 0 ? '   <-- these CANNOT be written back until the next import' : '');
    $out[] = '   Missing a version marker   : ' . $noEtag
           . ($noEtag > 0 ? '   <-- same: the next import fills these in' : '');
    if ($noRef > 0) {
        $out[] = '   FIX: run an import. Contacts imported before write-back existed did not';
        $out[] = '        record which card was theirs; every import records it from now on.';
    }

    // --- what has actually happened ---
    if ($hasLogTable) {
        $out[] = '';
        $out[] = '5. Write log';
        try {
            $rows = $conn->query("SELECT outcome, COUNT(*) c FROM carddav_write_log WHERE provider_id=$pid GROUP BY outcome")->fetchAll(PDO::FETCH_ASSOC);
            if (!$rows) {
                $out[] = '   Nothing has ever been written back through this address book.';
            } else {
                foreach ($rows as $r) $out[] = '   ' . str_pad($r['outcome'], 10) . ' : ' . $r['c'];
                $last = $conn->query("SELECT created_datetime, outcome, message FROM carddav_write_log WHERE provider_id=$pid ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
                if ($last) {
                    $out[] = '   Most recent: ' . $last['created_datetime'] . ' - ' . $last['outcome'];
                    $out[] = '                ' . $last['message'];
                }
                $out[] = '';
                $out[] = '   "conflict" is not a fault. It means somebody had already changed the';
                $out[] = '   same detail in the address book and FreeITSM refused to overwrite it.';
            }
        } catch (Throwable $e) {
            $out[] = '   Could not be read: ' . $e->getMessage();
        }
    }

    addSection($sections, 'ADDRESS BOOK #' . $pid . ' - ' . $p['display_name'], $out);
}

// ---------------------------------------------------------------- summary
$sum = [];
$sum[] = 'Address books configured: ' . count($providers);
$sum[] = '';
$sum[] = 'Read the rungs in order - they fail for different reasons:';
$sum[] = '  1 fails  -> a network, address or password problem (check the auth scheme).';
$sum[] = '  2 fails  -> the account signs in but cannot read that particular book.';
$sum[] = '  3 says NO -> the book is read-only for this account. Fix it on the server;';
$sum[] = '              nothing in FreeITSM can grant a permission the server withholds.';
$sum[] = '  4 shows missing card URLs -> run an import; they are recorded from now on.';
$sum[] = '';
$sum[] = 'To compare what FreeITSM holds against what the cards actually say, run D016.';
addSection($sections, 'SUMMARY', $sum);

emit_and_exit($sections);

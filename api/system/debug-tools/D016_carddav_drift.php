<?php
/**
 * Debug Tool D016 — Where do FreeITSM and the address book disagree?
 *
 * A top-to-bottom comparison: every person imported from a CardDAV address book,
 * against the card they came from, field by field.
 *
 * ⭐ THIS IS ALSO THE ANSWER TO A QUESTION NOBODY ANSWERED. #133 asked for
 * two-way sync; the reply asked the reporter which details actually drift, and
 * in which direction, because that is the one piece of evidence that would shape
 * it. No answer came. This measures it instead of asking — and it works on the
 * operator's own data, which is better evidence than anybody's recollection.
 *
 * 🔴 READ-ONLY. It changes nothing on either side, in either direction. It does
 * not write to the address book, it does not update `users`, and it deliberately
 * offers no "fix it" button: deciding which side is right is exactly the
 * judgement a person has to make, and a one-click reconcile over hundreds of
 * contacts is how a careful integration becomes a data-loss incident.
 *
 * ⚠️ It reads the WHOLE address book, so it is slower than the other tools here.
 * That is inherent - there is no way to ask a CardDAV server "which of these
 * changed" without fetching them.
 *
 * Output: plain text, section-delimited with === HEADERS ===.
 */

@session_start();

$DIAG_ID   = 'D016';
$DIAG_NAME = 'CardDAV drift — where FreeITSM and the address book disagree';

require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../includes/functions.php';

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
@set_time_limit(180);

require_once __DIR__ . '/../../../includes/encryption.php';
require_once __DIR__ . '/../../../includes/carddav_write.php';
require_once __DIR__ . '/../../../includes/users.php';

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
 * Prints every section, separated by blank lines, and stops - the end of the report, and
 * the early exit when a check cannot go on.
 *
 * @param mixed $sections  the report, a list of section strings as built by addSection()
 */
function emit_and_exit($sections) {
    echo implode("\n\n", $sections) . "\n";
    exit;
}
/**
 * Show a value, or say plainly that there isn't one. Never a bare blank.
 *
 * @param ?string $v  the value to show; null, empty or blank gives (empty)
 */
function shownVal(?string $v): string {
    $v = trim((string)$v);
    return $v === '' ? '(empty)' : $v;
}

$conn = connectToDatabase();

$providers = $conn->query("SELECT * FROM auth_providers WHERE protocol = 'carddav' ORDER BY id")
                  ->fetchAll(PDO::FETCH_ASSOC);
if (!$providers) {
    addSection($sections, 'ADDRESS BOOKS', ['None configured — there is nothing to compare.']);
    emit_and_exit($sections);
}

addSection($sections, 'WHAT THIS IS', [
    'Every person imported from an address book, compared with their card as it',
    'stands right now, field by field.',
    '',
    'Nothing is changed on either side. There is deliberately no "fix it" button:',
    'which side is right is a judgement, and a one-click reconcile over hundreds',
    'of contacts is how a careful integration becomes a data-loss incident.',
    '',
    'Only the five details an address book can hold are compared. Employee number',
    'and manager live in FreeITSM alone, so they cannot disagree with anything.',
]);

$grandTotals = ['same' => 0, 'differ' => 0, 'missing_card' => 0, 'no_ref' => 0, 'orphan_card' => 0];
$driftByField = [];

foreach ($providers as $p) {
    $pid  = (int)$p['id'];
    $out  = [];
    $book = trim((string)($p['carddav_addressbook'] ?? ''));
    $cfg  = cardDavConfigFromProvider($p);

    if ($book === '' || trim($cfg['url']) === '') {
        addSection($sections, 'ADDRESS BOOK #' . $pid . ' - ' . $p['display_name'],
            ['Not fully configured (no server address or no book chosen) - skipped.']);
        continue;
    }

    // --- everything the server has, keyed by UID ---
    $fetched = cardDavFetchCards($cfg, $book);
    if (!$fetched['ok']) {
        addSection($sections, 'ADDRESS BOOK #' . $pid . ' - ' . $p['display_name'], [
            'Could not read the address book: ' . $fetched['error'],
            '',
            'Run D015 first — it walks the connection one rung at a time and names',
            'which of reach / sign in / read / write is actually the problem.',
        ]);
        continue;
    }
    $byUid = [];
    foreach ($fetched['cards'] as $c) {
        $mapped = cdsyncMapCard($c['vcard'], $c['href']);
        if ($mapped === null) continue;               // group card, or no UID
        $byUid[$mapped['guid']] = $mapped;
    }

    // --- everything WE have from this provider ---
    $stmt = $conn->prepare(
        "SELECT u.id, u.display_name, u.job_title, u.department, u.office, u.phone, u.mobile,
                i.subject AS uid, i.source_ref, i.source_etag
           FROM users u
           JOIN user_sso_identities i ON i.user_id = u.id AND i.provider_id = ?
          WHERE u.is_managed = 1 AND u.auth_provider_id = ?
       ORDER BY u.display_name"
    );
    $stmt->execute([$pid, $pid]);
    $ours = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $same = 0; $rows = []; $seenUids = [];
    foreach ($ours as $u) {
        $uid = (string)$u['uid'];
        $seenUids[$uid] = true;

        if (!isset($byUid[$uid])) {
            $grandTotals['missing_card']++;
            $rows[] = sprintf("  %-28s  NO LONGER ON THE SERVER", mb_substr((string)$u['display_name'], 0, 28));
            $rows[] = "      Their card has gone from the address book. FreeITSM never deletes";
            $rows[] = "      anybody, so they are still here; the import marks them as left after";
            $rows[] = "      the configured number of missed runs.";
            continue;
        }
        $card = $byUid[$uid];
        if (trim((string)$u['source_ref']) === '') $grandTotals['no_ref']++;

        $diffs = [];
        foreach (USER_CARDDAV_OWNED as $f) {
            $mine   = trim((string)($u[$f] ?? ''));
            $theirs = trim((string)($card[$f] ?? ''));
            if ($mine === $theirs) continue;
            $diffs[] = $f;
            $driftByField[$f] = ($driftByField[$f] ?? 0) + 1;
            $rows[] = sprintf("      %-11s FreeITSM: %-28s card: %s",
                str_replace('_', ' ', $f), shownVal($mine), shownVal($theirs));
        }
        if (!$diffs) { $same++; $grandTotals['same']++; continue; }

        $grandTotals['differ']++;
        // The header goes ABOVE the lines just written, so build it and splice.
        array_splice($rows, count($rows) - count($diffs), 0, [
            sprintf("  %-28s  %d differ", mb_substr((string)$u['display_name'], 0, 28), count($diffs))
        ]);
        if (trim((string)$u['source_ref']) === '') {
            $rows[] = "      ⚠️ No card URL recorded, so a write-back cannot reach this card";
            $rows[] = "         until the next import.";
        }
        $rows[] = '';
    }

    // Cards on the server that never became people here.
    $orphans = [];
    foreach ($byUid as $uid => $card) {
        if (isset($seenUids[$uid])) continue;
        $orphans[] = $card['name'] . ($card['email'] ? ' <' . $card['email'] . '>' : '');
        $grandTotals['orphan_card']++;
    }

    $out[] = 'People imported from this book : ' . count($ours);
    $out[] = 'Contacts on the server         : ' . count($byUid);
    $out[] = 'In step                        : ' . $same;
    $out[] = 'Disagreeing                    : ' . (count($ours) - $same);
    $out[] = '';
    if ($rows) {
        $out[] = 'WHERE THEY DISAGREE';
        $out[] = '(FreeITSM\'s value first, the card\'s value second)';
        $out[] = '';
        $out = array_merge($out, $rows);
    } else {
        $out[] = 'Every imported person matches their card exactly.';
    }

    if ($orphans) {
        $out[] = '';
        $out[] = 'ON THE SERVER BUT NOT HERE (' . count($orphans) . ')';
        $out[] = 'Usually correct — these are outside the group or tag you chose to import.';
        foreach (array_slice($orphans, 0, 40) as $o) $out[] = '  ' . $o;
        if (count($orphans) > 40) $out[] = '  ... and ' . (count($orphans) - 40) . ' more';
    }

    addSection($sections, 'ADDRESS BOOK #' . $pid . ' - ' . $p['display_name'], $out);
}

// ---------------------------------------------------------------- summary
$sum = [];
$sum[] = 'In step               : ' . $grandTotals['same'];
$sum[] = 'Disagreeing           : ' . $grandTotals['differ'];
$sum[] = 'Card gone from server : ' . $grandTotals['missing_card'];
$sum[] = 'On server, not here   : ' . $grandTotals['orphan_card'];
$sum[] = 'Missing a card URL    : ' . $grandTotals['no_ref'];
$sum[] = '';

if ($driftByField) {
    arsort($driftByField);
    $sum[] = 'WHICH DETAILS DRIFT MOST';
    foreach ($driftByField as $f => $n) {
        $sum[] = '  ' . str_pad(str_replace('_', ' ', $f), 12) . ' : ' . $n;
    }
    $sum[] = '';
    $sum[] = 'This is the evidence worth having. It says which details are worth keeping';
    $sum[] = 'in step, measured on your own data rather than guessed at.';
} else {
    $sum[] = 'Nothing is out of step.';
}

$sum[] = '';
$sum[] = 'WHAT TO DO ABOUT A DISAGREEMENT';
$sum[] = '  The card is right  -> run an import. It overwrites FreeITSM from the card.';
$sum[] = '  FreeITSM is right  -> switch on "write changes back", then open the person';
$sum[] = '                        and re-save the detail. It is sent to the card.';
$sum[] = '  ⚠️ With write-back ON, a difference like these is exactly what makes a save';
$sum[] = '     get REFUSED rather than written — FreeITSM will not overwrite a value it';
$sum[] = '     did not last import. Import first, then edit.';
addSection($sections, 'SUMMARY', $sum);

emit_and_exit($sections);

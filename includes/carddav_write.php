<?php
/**
 * CardDAV write-back — editing a contact card without rewriting it.
 *
 * Asked for in https://github.com/edmozley/freeitsm/issues/133, where the
 * reporter's argument is that a telephone number changes DURING the phone call,
 * and having to then correct it in a second application is exactly what an ITSM
 * tool should stop you doing.
 *
 * ──────────────────────────────────────────────────────────────────────────
 * 🔴🔴 WHY THIS IS A SEPARATE FILE FROM includes/carddav.php.
 *
 * The import is provably read-only: it issues `PROPFIND` and `REPORT` and
 * nothing else, which is the honest answer when an operator asks whether
 * connecting FreeITSM can damage their address book. `PUT` lives here, in a file
 * the import path never loads, so that guarantee stays a fact about the code
 * rather than a promise about intentions.
 * ──────────────────────────────────────────────────────────────────────────
 *
 * 🔴🔴 AND THE ONE RULE THAT SHAPES EVERYTHING BELOW: NEVER GENERATE A VCARD.
 *
 * The obvious implementation is to take the seven person fields, render a vCard
 * and PUT it. That silently destroys everything on the card FreeITSM does not
 * model — the photo, the birthday, the notes, the street and postcode (only the
 * town is imported), every `X-` property somebody's CRM writes, and the
 * `CATEGORIES` line that decides whether the card is even in scope on the next
 * run. It would look like it worked. The operator would discover it months
 * later, with no way back.
 *
 * So this edits the bytes the server gave us. A property whose value changed has
 * its line rewritten in place; every other line, understood or not, is passed
 * through untouched. That also disposes of the vCard 3.0 versus 4.0 question,
 * which otherwise needs a policy: the card keeps whatever `VERSION` it already
 * declares, because nothing rewrites it.
 *
 * ⚠️ The safety here is deliberately ASYMMETRIC with the import. A bad import
 * damages OUR data and can be re-imported. A bad write-back damages the
 * operator's address book and cannot be undone by us at all. So write-back
 * touches only fields FreeITSM owns, only on cards it imported, and only when a
 * human deliberately edited one — there is no bulk reconciliation pass, and
 * there should never be one.
 */

require_once __DIR__ . '/carddav.php';
require_once __DIR__ . '/carddav_sync.php';

/** Longest a single unfolded content line may be, in octets, before folding. */
const CARDDAV_FOLD_AT = 75;

/**
 * Write a card back, refusing if it has changed on the server since we read it.
 *
 * 🔴🔴 `If-Match` IS THE WHOLE SAFETY MODEL AND IS NOT OPTIONAL. Without it a
 * `PUT` is last-writer-wins: an analyst edits a phone number, and in doing so
 * silently reverts the address the customer's own office updated an hour ago in
 * Outlook, because our copy of the card was fetched last night. With it, the
 * server compares ETags and answers 412 — and 412 is not a failure, it is the
 * feature. It means "somebody else got there first", and the honest response is
 * to re-read the card and apply the change to the current version.
 *
 * ⚠️ An EMPTY etag is refused outright rather than sent as `If-Match: *`, which
 * means "any existing card" and would be exactly the unguarded overwrite this
 * exists to prevent. No stored ETag means we have not really read this card, and
 * the correct move is to fetch it, not to write over it.
 *
 * @param array $cfg  request settings from cardDavConfigFromProvider()
 * @param string $cardUrl  the card's absolute URL
 * @param string $vcard  the whole card to store, already edited
 * @param string $etag  the ETag the card had when it was read, sent as If-Match; '' refuses the write
 * @return array ['ok'=>bool, 'status'=>int, 'etag'=>string, 'conflict'=>bool,
 *                'error'=>string]  `etag` is the card's NEW tag when the server
 *                returned one; '' means re-read before writing again.
 */
function cardDavPutCard(array $cfg, string $cardUrl, string $vcard, string $etag): array
{
    // `body` is the server's own response, kept for the write log. A tidied
    // message is the one thing that cannot diagnose a server nobody here can
    // log in to — and DAV servers put the real reason in an XML error body.
    $out = ['ok' => false, 'status' => 0, 'etag' => '', 'conflict' => false, 'error' => '', 'body' => ''];

    if (trim($etag) === '') {
        $out['error'] = 'FreeITSM has no version marker for this contact, so it will not '
                      . 'overwrite it. The next import will pick one up.';
        return $out;
    }

    $res = cardDavRequest($cfg, 'PUT', $cardUrl, $vcard, [
        // charset declared: a server that guesses will guess Latin-1 on some
        // setups and mangle every accented name in the book.
        'Content-Type: text/vcard; charset=utf-8',
        'If-Match: ' . $etag,
    ]);

    $out['status'] = $res['status'];
    $out['body']   = $res['body'];

    // 412 Precondition Failed, and 409 which a few servers send for the same
    // thing. Reported as a conflict rather than an error so the caller can offer
    // "reload and try again" instead of "something went wrong".
    if ($res['status'] === 412 || $res['status'] === 409) {
        $out['conflict'] = true;
        $out['error']    = 'This contact was changed in the address book since FreeITSM last '
                         . 'read it, so the change was not written. Import again to pick up '
                         . 'their version first.';
        return $out;
    }

    if (!$res['ok']) {
        $out['error'] = $res['error'] !== '' ? $res['error'] : cardDavExplainStatus($res['status']);
        return $out;
    }

    $out['ok'] = true;
    // Most servers return the new ETag; some return none and expect a re-read.
    // ⚠️ Stored only when present — inventing one would make the NEXT write
    // think it holds a current version when it does not.
    $out['etag'] = trim($res['headers']['etag'] ?? '');
    return $out;
}

/**
 * Fetch one card by its own URL, with the ETag the server currently holds.
 *
 * @param array $cfg  request settings from cardDavConfigFromProvider()
 * @param string $cardUrl  the card's absolute URL
 * @return array ['ok'=>bool, 'vcard'=>string, 'etag'=>string, 'error'=>string]
 */
function cardDavGetCard(array $cfg, string $cardUrl): array
{
    $out = ['ok' => false, 'vcard' => '', 'etag' => '', 'error' => '', 'status' => 0, 'body' => ''];
    $res = cardDavRequest($cfg, 'GET', $cardUrl, '', ['Accept: text/vcard, text/x-vcard, */*']);
    $out['status'] = $res['status'];
    $out['body']   = $res['body'];

    if (!$res['ok']) {
        $out['error'] = $res['error'] !== '' ? $res['error'] : cardDavExplainStatus($res['status']);
        return $out;
    }
    if (stripos($res['body'], 'BEGIN:VCARD') === false) {
        // ⚠️ A 200 that is not a vCard is usually a login page from a proxy in
        // front of the server. Editing it and PUTting it back would replace
        // somebody's contact with an HTML document.
        $out['error'] = 'The server returned something that is not a contact card.';
        return $out;
    }
    $out['ok']    = true;
    $out['vcard'] = $res['body'];
    $out['etag']  = trim($res['headers']['etag'] ?? '');
    return $out;
}

/**
 * Does this account have permission to WRITE to the address book?
 *
 * 🔑 The server is asked rather than guessed at, and no list of "CardDAV servers
 * FreeITSM supports" is kept anywhere. Writing is plain `PUT` from RFC 6352 —
 * the same standard as the reads — so any server the import works against can in
 * principle be written to. What actually varies is PERMISSION: a shared or
 * subscribed address book is commonly read-only, and the alternative to asking
 * is finding out by failing halfway through somebody's edit.
 *
 * ⚠️ A server that does not report `current-user-privilege-set` at all is
 * treated as UNKNOWN, not as permitted. The setting then stays off, and the
 * screen says the server did not answer rather than pretending it said no.
 *
 * @param array $cfg  request settings from cardDavConfigFromProvider()
 * @param string $bookHref  the address book's href, absolute or relative to $cfg['url']
 * @return array ['writable'=>bool, 'known'=>bool, 'privileges'=>string[], 'error'=>string]
 */
function cardDavCanWrite(array $cfg, string $bookHref): array
{
    $out = ['writable' => false, 'known' => false, 'privileges' => [], 'error' => ''];

    $url = cardDavAbsoluteUrl($cfg['url'] ?? '', $bookHref);
    $res = cardDavRequest($cfg, 'PROPFIND', $url,
        '<?xml version="1.0" encoding="utf-8"?>' .
        '<d:propfind xmlns:d="DAV:"><d:prop><d:current-user-privilege-set/></d:prop></d:propfind>',
        ['Depth: 0', 'Content-Type: application/xml; charset=utf-8']
    );

    if (!$res['ok']) {
        $out['error'] = $res['error'] !== '' ? $res['error'] : cardDavExplainStatus($res['status']);
        return $out;
    }

    $prev = libxml_use_internal_errors(true);
    $doc  = new DOMDocument();
    $okXml = $doc->loadXML($res['body']);
    libxml_clear_errors();
    libxml_use_internal_errors($prev);
    if (!$okXml) {
        $out['error'] = 'The server answered, but not with XML this could read.';
        return $out;
    }

    $xp = new DOMXPath($doc);
    $xp->registerNamespace('d', 'DAV:');
    $nodes = $xp->query('//d:current-user-privilege-set/d:privilege/*');
    if ($nodes === false || $nodes->length === 0) {
        $out['error'] = 'This server does not report what permissions the account has, so '
                      . 'FreeITSM cannot confirm it is allowed to write.';
        return $out;
    }

    foreach ($nodes as $n) $out['privileges'][] = $n->localName;
    $out['known'] = true;
    // `all` implies everything; `write` covers content and properties; a server
    // may grant only `write-content`, which is what a PUT to an existing card
    // actually needs.
    $out['writable'] = (bool)array_intersect($out['privileges'], ['all', 'write', 'write-content']);
    return $out;
}

/**
 * Fold one logical line the way RFC 6350 §3.2 asks: break at 75 octets and begin
 * each continuation with a single space.
 *
 * 🔴 UTF-8 AWARE, and it has to be. The limit is defined in OCTETS, not
 * characters, so a naive `substr($s, 0, 75)` will happily cut a multi-byte
 * character in half — and the two halves then sit either side of a fold, so the
 * receiving parser rejoins them into a byte sequence that is not valid UTF-8.
 * A Polish or Danish address book, which FreeITSM has real users running, hits
 * this on ordinary data rather than as an edge case.
 *
 * ⚠️ Folds on a CONTINUATION BYTE boundary (`10xxxxxx`), walking back at most
 * three bytes, so a character is never split.
 *
 * @param string $logical  one unfolded content line
 * @param string $eol  the line ending to fold with - the card's own, from cardDavDetectEol()
 */
function cardDavFoldLine(string $logical, string $eol = "\r\n"): string
{
    if (strlen($logical) <= CARDDAV_FOLD_AT) return $logical;

    $out   = '';
    $start = 0;
    $limit = CARDDAV_FOLD_AT;
    $len   = strlen($logical);

    while ($start < $len) {
        $take = min($limit, $len - $start);
        if ($start + $take < $len) {
            // Walk back off a continuation byte so no character is cut in two.
            $guard = 0;
            while ($take > 1 && $guard < 4
                   && (ord($logical[$start + $take]) & 0xC0) === 0x80) {
                $take--; $guard++;
            }
        }
        $out .= ($start === 0 ? '' : $eol . ' ') . substr($logical, $start, $take);
        $start += $take;
        // Continuations carry a leading space, which counts toward the 75.
        $limit = CARDDAV_FOLD_AT - 1;
    }
    return $out;
}

/**
 * Which line ending does this card use?
 *
 * ⚠️ Asked rather than assumed. The spec says CRLF, and most servers comply, but
 * plenty of cards in the wild are stored LF-only — and a file that mixes the two
 * after an edit is the kind of thing that parses fine everywhere except the one
 * client the operator actually uses.
 *
 * @param string $raw  the card exactly as the server returned it
 */
function cardDavDetectEol(string $raw): string
{
    return str_contains($raw, "\r\n") ? "\r\n" : "\n";
}

/**
 * Split a card into logical lines, remembering where each one physically began
 * and ended.
 *
 * This is the counterpart to cardDavUnfold(), which throws that away. Editing
 * needs it: to replace the logical line `TITLE:Head of Sales` we must know which
 * physical lines it occupied, continuations included, so the replacement lands
 * in the right place and takes the fold with it.
 *
 * @param string $raw  the card exactly as the server returned it
 * @return array of ['logical' => string, 'start' => int, 'end' => int] where the
 *               offsets are byte positions into $raw and `end` is exclusive of
 *               the line ending.
 */
function cardDavLineSpans(string $raw): array
{
    $spans = [];
    $len   = strlen($raw);
    $i     = 0;

    while ($i < $len) {
        $nl = strpos($raw, "\n", $i);
        $lineEnd = $nl === false ? $len : $nl;
        // Trim a CR that belongs to the line ending, not to the content.
        $contentEnd = ($lineEnd > $i && $raw[$lineEnd - 1] === "\r") ? $lineEnd - 1 : $lineEnd;
        $text = substr($raw, $i, $contentEnd - $i);

        if ($spans && $text !== '' && ($text[0] === ' ' || $text[0] === "\t")) {
            // A continuation: append to the previous logical line, minus the one
            // leading space or tab, and extend that line's span over it.
            $last = count($spans) - 1;
            $spans[$last]['logical'] .= substr($text, 1);
            $spans[$last]['end'] = $contentEnd;
        } elseif ($text !== '') {
            $spans[] = ['logical' => $text, 'start' => $i, 'end' => $contentEnd];
        }

        if ($nl === false) break;
        $i = $nl + 1;
    }
    return $spans;
}

/**
 * The property name of a logical line, upper-cased, or '' if it is not one.
 *
 * ⚠️ Strips a group prefix. `item1.TEL;TYPE=WORK:…` is how Apple Contacts writes
 * a property it wants to attach a label to, and reading the name as `ITEM1.TEL`
 * means every such line is invisible to both the reader and the writer.
 *
 * @param string $logical  one unfolded content line
 */
function cardDavLineProperty(string $logical): string
{
    $colon = strpos($logical, ':');
    if ($colon === false) return '';
    $left = substr($logical, 0, $colon);
    $semi = strpos($left, ';');
    $name = $semi === false ? $left : substr($left, 0, $semi);
    $dot  = strrpos($name, '.');
    if ($dot !== false) $name = substr($name, $dot + 1);
    return strtoupper(trim($name));
}

/**
 * Replace the VALUE of the logical line at $index, keeping its name, its group
 * prefix and every one of its parameters exactly as they were.
 *
 * 🔑 The parameters are the point. A line is `TEL;TYPE=WORK;X-ABLabel=Reception:
 * 0123` and only the bit after the colon is ours to change — rewriting the whole
 * line from a property name and a value would quietly drop the label, the type,
 * and any vendor parameter that made the entry meaningful in the client that
 * created it.
 *
 * @param string $raw  the card as it stands
 * @param array $spans  cardDavLineSpans() of that same $raw
 * @param int $index  which span's value to replace
 * @param string $newValue ALREADY ESCAPED — this function does not escape, so
 *                         that a structured value assembled from components
 *                         (each escaped separately) can be passed through whole.
 */
function cardDavReplaceLineValue(string $raw, array $spans, int $index, string $newValue): string
{
    $span  = $spans[$index];
    $colon = strpos($span['logical'], ':');
    if ($colon === false) return $raw;

    $eol      = cardDavDetectEol($raw);
    $rebuilt  = substr($span['logical'], 0, $colon + 1) . $newValue;
    $replaced = cardDavFoldLine($rebuilt, $eol);

    return substr($raw, 0, $span['start']) . $replaced . substr($raw, $span['end']);
}

/**
 * Add a property that the card does not currently have, immediately before
 * `END:VCARD`.
 *
 * ⚠️ Before END:VCARD rather than appended, obviously — but also NOT at the top,
 * because `BEGIN`, `VERSION` and often `UID` are expected in that order by
 * stricter parsers and inserting above them has broken clients before.
 *
 * Returns the card unchanged if there is no END:VCARD to insert before: a card
 * that malformed is not one to start editing.
 *
 * @param string $raw  the card as it stands
 * @param string $logicalLine  the whole new content line, value already escaped; folded here
 */
function cardDavInsertLine(string $raw, string $logicalLine): string
{
    $spans = cardDavLineSpans($raw);
    $eol   = cardDavDetectEol($raw);

    for ($i = count($spans) - 1; $i >= 0; $i--) {
        if (strtoupper(trim($spans[$i]['logical'])) === 'END:VCARD') {
            return substr($raw, 0, $spans[$i]['start'])
                 . cardDavFoldLine($logicalLine, $eol) . $eol
                 . substr($raw, $spans[$i]['start']);
        }
    }
    return $raw;
}

/**
 * Remove the logical line at $index, its continuations and its line ending.
 *
 * @param string $raw  the card as it stands
 * @param array $spans  cardDavLineSpans() of that same $raw
 * @param int $index  which span to remove
 */
function cardDavRemoveLine(string $raw, array $spans, int $index): string
{
    $span = $spans[$index];
    $end  = $span['end'];
    // Take the line ending with it, or the card grows a blank line per removal.
    if (substr($raw, $end, 2) === "\r\n")      $end += 2;
    elseif (substr($raw, $end, 1) === "\n")    $end += 1;
    return substr($raw, 0, $span['start']) . substr($raw, $end);
}

/**
 * Set one component of a structured value, leaving its siblings alone.
 *
 * `ORG:Acme Ltd;Finance` and `ADR:;;12 High St;Norwich;;NR1 1AA;UK` both hold
 * several fields in one line, separated by unescaped semicolons, and FreeITSM
 * imports exactly one component from each — the department from ORG, the town
 * from ADR. Writing the whole value back would erase the company name and the
 * entire street address respectively.
 *
 * ⚠️ Pads with empty components when the stored value is shorter than the index
 * being written. A card carrying only `ADR:;;12 High St` has no town slot at
 * all, and appending one requires the two empty separators in between or the
 * town lands in `street`.
 *
 * @param string $value  the property's current value after the colon, still escaped; '' for a new line
 * @param int $index  which component to set, from 0 (ORG department is 1, ADR town is 3)
 * @param int $arity how many components the property is defined to have, so a
 *                   padded value is not left shorter than parsers expect
 * @param string $escapedNew  the new component, already escaped with cardDavEscapeText(); '' blanks it
 */
function cardDavSetComponent(string $value, int $index, int $arity, string $escapedNew): string
{
    $parts = preg_split('/(?<!\\\\);/', $value);
    if ($parts === false) $parts = [''];
    while (count($parts) < max($index + 1, $arity)) $parts[] = '';
    $parts[$index] = $escapedNew;
    return implode(';', $parts);
}

/**
 * How each writable person field is stored on a card.
 *
 * ⚠️ Exactly the five in USER_CARDDAV_OWNED, and for the same reason: a vCard
 * has nowhere to keep a payroll number, and almost nothing writes
 * `RELATED;TYPE=manager`, so employee ID and the reporting line are FreeITSM's
 * own and are never sent to the server.
 *
 *   kind 'text'      — the whole value after the colon
 *   kind 'component' — one field inside a semicolon-separated value
 */
function cardDavWritableFields(): array
{
    return [
        'job_title'  => ['kind' => 'text',      'property' => 'TITLE'],
        'phone'      => ['kind' => 'text',      'property' => 'TEL', 'locator' => 'phone'],
        'mobile'     => ['kind' => 'text',      'property' => 'TEL', 'locator' => 'mobile'],
        // ORG:organisation;department — component 1, of 2.
        'department' => ['kind' => 'component', 'property' => 'ORG', 'index' => 1, 'arity' => 2],
        // ADR:pobox;extended;street;locality;region;postcode;country — the town
        // is component 3, and the property always has 7.
        'office'     => ['kind' => 'component', 'property' => 'ADR', 'index' => 3, 'arity' => 7],
    ];
}

/**
 * The line a given field's value lives on, or null if the card has no such line.
 *
 * 🔑 For the two telephone numbers this defers to cdsyncPhoneLines(), the SAME
 * selector the importer reads through. "The mobile" is a selection rule over
 * several `TEL` lines rather than a property name, and a writer with its own
 * copy of that rule would eventually disagree with the reader — overwriting the
 * desk number when asked to change the mobile, silently and with no error.
 *
 * @param array $spans  cardDavLineSpans() of the card
 * @param string $field  a person field from cardDavWritableFields(), e.g. 'mobile'; any other gives null
 */
function cardDavLocateField(array $spans, string $field): ?int
{
    $spec = cardDavWritableFields()[$field] ?? null;
    if ($spec === null) return null;

    if (($spec['locator'] ?? '') !== '') {
        $logical = array_map(function ($s) { return $s['logical']; }, $spans);
        $at = cdsyncPhoneLines($logical);
        return $at[$spec['locator']];
    }

    foreach ($spans as $i => $s) {
        if (cardDavLineProperty($s['logical']) === $spec['property']) return $i;
    }
    return null;
}

/**
 * Apply a set of FreeITSM field changes to a card, surgically.
 *
 * @param string $raw     the card exactly as the server returned it
 * @param array  $changes field => new plain value (null or '' clears it)
 *
 * @return array ['card' => string, 'changed' => string[], 'skipped' => array]
 *               `changed` names the fields actually written; `skipped` maps a
 *               field to why it was not.
 */
function cardDavApplyPersonEdits(string $raw, array $changes): array
{
    $specs   = cardDavWritableFields();
    $changed = [];
    $skipped = [];
    $card    = $raw;

    foreach ($changes as $field => $newValue) {
        $spec = $specs[$field] ?? null;
        if ($spec === null) {
            // Not an error: employee_id and manager_id reach here on any ordinary
            // save and are simply not the address book's business.
            $skipped[$field] = 'not stored on a vCard';
            continue;
        }

        // 🔴 Re-read the spans after EVERY edit. They are byte offsets into the
        // card, and the previous edit has just moved every offset after it —
        // reusing a stale set writes the second change into the middle of an
        // unrelated line. This is the whole reason the function loops rather
        // than computing all the edits and applying them at once.
        $spans = cardDavLineSpans($card);
        $index = cardDavLocateField($spans, $field);
        $plain = $newValue === null ? '' : trim((string)$newValue);

        if ($spec['kind'] === 'text') {
            if ($index === null) {
                if ($plain === '') { $skipped[$field] = 'absent on the card, and cleared here'; continue; }
                // A new property needs the parameters that make it mean what we
                // intend. An untyped TEL added as "the mobile" would be read back
                // as the desk number by our own importer on the next run.
                $line = $spec['property']
                      . ($field === 'mobile' ? ';TYPE=CELL' : ($field === 'phone' ? ';TYPE=WORK' : ''))
                      . ':' . cardDavEscapeText($plain);
                $card = cardDavInsertLine($card, $line);
                $changed[] = $field;
                continue;
            }
            $colon   = strpos($spans[$index]['logical'], ':');
            $current = cardDavUnescapeText(substr($spans[$index]['logical'], $colon + 1));
            if (trim($current) === $plain) continue;   // nothing to do
            $card = $plain === ''
                ? cardDavRemoveLine($card, $spans, $index)
                : cardDavReplaceLineValue($card, $spans, $index, cardDavEscapeText($plain));
            $changed[] = $field;
            continue;
        }

        // A component of a structured value.
        if ($index === null) {
            if ($plain === '') { $skipped[$field] = 'absent on the card, and cleared here'; continue; }
            $value = cardDavSetComponent('', $spec['index'], $spec['arity'], cardDavEscapeText($plain));
            $card  = cardDavInsertLine($card, $spec['property'] . ':' . $value);
            $changed[] = $field;
            continue;
        }
        $colon   = strpos($spans[$index]['logical'], ':');
        $value   = substr($spans[$index]['logical'], $colon + 1);
        $parts   = preg_split('/(?<!\\\\);/', $value);
        $current = cardDavUnescapeText($parts[$spec['index']] ?? '');
        if (trim($current) === $plain) continue;
        // 🔑 An emptied component is blanked IN PLACE, never removed: deleting
        // the ADR line because somebody cleared the office would take the street
        // and the postcode with it.
        $updated = cardDavSetComponent($value, $spec['index'], $spec['arity'],
                                       $plain === '' ? '' : cardDavEscapeText($plain));
        $card = cardDavReplaceLineValue($card, $spans, $index, $updated);
        $changed[] = $field;
    }

    return ['card' => $card, 'changed' => $changed, 'skipped' => $skipped];
}

/** How much of a server's response to keep. Enough for a DAV error body. */
const CARDDAV_LOG_RESPONSE_MAX = 4000;

/**
 * Record one write-back attempt.
 *
 * 🔴 THE ATTEMPT, NOT THE SUCCESS — and this is the whole point. An import
 * records every run and every person it touched. Write-back, the half that
 * changes SOMEBODY ELSE'S data, recorded nothing until this existed, so
 * "it isn't writing" had no evidence behind it at all. The three answers the
 * operator needs to tell apart are *we never tried*, *the server said no*, and
 * *we refused on purpose because their copy had changed* — and only the last
 * one is FreeITSM working as intended.
 *
 * 🔑 `$response` is stored VERBATIM (truncated, not summarised). A DAV server
 * puts the real reason in an XML error body, and paraphrasing it throws away the
 * one thing that can diagnose a server nobody here can log in to.
 *
 * ⚠️ NEVER THROWS. This is called on the failure path of a feature that is
 * itself already reported as failed; a logging error must not become the thing
 * the analyst sees instead.
 *
 * @param int $providerId  auth_providers.id of the address book
 * @param ?int $userId  users.id of the person; null or 0 stores NULL
 * @param string $displayName  the person's name, kept so the row still says who it was after a delete
 * @param string $outcome  'ok', 'created', 'conflict', 'failed' or 'skipped'
 * @param array $fields  the person fields involved, e.g. ['mobile']
 * @param ?int $status  the HTTP status the server gave; null or 0 when no request was made
 * @param string $response  the server's response body, stored verbatim up to CARDDAV_LOG_RESPONSE_MAX
 * @param string $message  a plain sentence for the write log; cut to 1000 characters
 */
function cardDavLogWrite(PDO $conn, int $providerId, ?int $userId, string $displayName,
                         string $outcome, array $fields, ?int $status, string $response,
                         string $message, ?int $analystId): void
{
    try {
        $conn->prepare(
            "INSERT INTO carddav_write_log
                (provider_id, user_id, display_name, outcome, fields, http_status,
                 server_response, message, triggered_by_analyst_id, created_datetime)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, UTC_TIMESTAMP())"
        )->execute([
            $providerId,
            $userId ?: null,
            $displayName !== '' ? mb_substr($displayName, 0, 255) : null,
            $outcome,
            $fields ? mb_substr(implode(', ', $fields), 0, 255) : null,
            $status ?: null,
            $response !== '' ? mb_substr($response, 0, CARDDAV_LOG_RESPONSE_MAX) : null,
            mb_substr($message, 0, 1000),
            $analystId ?: null,
        ]);
    } catch (Throwable $e) {
        error_log('[carddav-write] could not write the log row: ' . $e->getMessage());
    }
}

/**
 * Push a person's changed fields to their card in the address book.
 *
 * ──────────────────────────────────────────────────────────────────────────
 * 🔴🔴 THE CONCURRENCY DESIGN, WHICH IS THE HARD PART AND NOT OBVIOUS.
 *
 * There are three plausible schemes and two of them are wrong.
 *
 *   WRONG 1 — PUT with the ETag stored at import time. Safe, but it refuses
 *   whenever ANY field on the card changed since the last sync, including ones
 *   FreeITSM does not even read. An analyst correcting a phone number would be
 *   blocked because somebody added a birthday last week.
 *
 *   WRONG 2 — GET the card, edit it, PUT with the ETag that GET just returned.
 *   This never refuses anything, which sounds convenient and in fact means the
 *   concurrency guard has been switched off: `If-Match` can no longer fail,
 *   because we just asked the server which value would make it pass.
 *
 *   WHAT THIS DOES — GET the card fresh, and before editing, compare the
 *   server's CURRENT value of each field being written against the value
 *   FreeITSM held before this save. If they differ, somebody else changed that
 *   same field, and the write is refused as a real conflict. If they match, the
 *   edit is applied to the fresh bytes so everybody else's changes to other
 *   properties survive, and the PUT carries the fresh ETag purely to close the
 *   few milliseconds between the GET and the PUT.
 *
 * That is a per-field three-way merge, and it is the only one of the three that
 * both preserves other people's work and notices when two people changed the
 * same thing.
 * ──────────────────────────────────────────────────────────────────────────
 *
 * 🔴 THIS NEVER FAILS THE CALLER'S SAVE. FreeITSM's own record has already been
 * written by the time this runs, and rolling it back because somebody else's
 * server is unreachable would throw away the analyst's work to report a problem
 * they cannot do anything about. Every outcome is returned for the UI to
 * mention, and none of them is an exception.
 *
 * ⚠️ Except for the self-service portal, which calls this BEFORE its save and
 * saves only on success (api/self-service/update_profile.php says why). The
 * function itself is the same either way; `$fromPortal` only changes what the
 * write log says about who made the change.
 *
 * @param int $userId  users.id of the person whose card is updated
 * @param array $changes  field => the value just saved
 * @param array $previous field => the value FreeITSM held BEFORE this save
 * @param bool  $fromPortal the person changed it themselves, in the portal
 *
 * @return array ['attempted'=>bool, 'ok'=>bool, 'changed'=>string[],
 *                'conflict'=>bool, 'error'=>string, 'reason'=>string]
 *               `reason` explains an attempted=false, which is the ordinary case
 *               for every person who is not a CardDAV contact.
 */
function cardDavPushPersonChanges(PDO $conn, int $userId, array $changes, array $previous,
                                  ?int $analystId = null, bool $fromPortal = false): array
{
    $out = ['attempted' => false, 'ok' => false, 'changed' => [],
            'conflict' => false, 'error' => '', 'reason' => ''];

    // Only fields a vCard can hold. employee_id and manager_id reach here on an
    // ordinary save and are simply not the address book's business.
    $writable = array_intersect_key($changes, cardDavWritableFields());
    if (!$writable) { $out['reason'] = 'no address-book fields changed'; return $out; }

    try {
        $stmt = $conn->prepare(
            "SELECT p.*, i.source_ref, i.source_etag, i.id AS identity_id
               FROM users u
               JOIN auth_providers p ON p.id = u.auth_provider_id
               JOIN user_sso_identities i ON i.user_id = u.id AND i.provider_id = p.id
              WHERE u.id = ? AND u.is_managed = 1 AND p.protocol = 'carddav'"
        );
        $stmt->execute([$userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Throwable $e) {
        error_log('[carddav-write] provider lookup failed: ' . $e->getMessage());
        $out['reason'] = 'the address book could not be looked up';
        return $out;
    }

    if (!$row) { $out['reason'] = 'not an address-book contact'; return $out; }
    if ((int)($row['carddav_write_back'] ?? 0) !== 1) {
        $out['reason'] = 'writing changes back is switched off for this address book';
        return $out;
    }
    if (trim((string)$row['source_ref']) === '') {
        // Imported before write-back existed, so no card URL was recorded. The
        // next sync fills it in; saying so beats a silent no-op.
        $out['reason'] = 'FreeITSM does not know which card belongs to this contact yet '
                       . '(it will after the next import)';
        return $out;
    }

    $out['attempted'] = true;
    $cfg = cardDavConfigFromProvider($row);
    // 🔴 Interactive: this runs inside somebody's save, so it uses the short
    // timeouts. An unreachable server must cost a few seconds, not twenty.
    $cfg['interactive'] = true;
    $url = cardDavAbsoluteUrl($cfg['url'], (string)$row['source_ref']);

    $pid  = (int)$row['id'];
    $name = (string)($previous['display_name'] ?? '');

    $got = cardDavGetCard($cfg, $url);
    if (!$got['ok']) {
        $out['error'] = $got['error'];
        cardDavLogWrite($conn, $pid, $userId, $name, 'failed', [], $got['status'] ?? null,
                        $got['body'] ?? '', 'Could not read the contact card: ' . $got['error'], $analystId);
        return $out;
    }

    // --- the three-way merge, field by field ---
    $server = cdsyncMapCard($got['vcard'], (string)$row['source_ref']);
    if ($server === null) {
        $out['error'] = 'That card could not be read as a contact, so it was left alone.';
        cardDavLogWrite($conn, $pid, $userId, $name, 'failed', [], null, $got['vcard'],
                        $out['error'], $analystId);
        return $out;
    }

    $apply = [];
    foreach ($writable as $field => $newValue) {
        $theirs = trim((string)($server[$field] ?? ''));
        $ours   = trim((string)($previous[$field] ?? ''));
        $want   = trim((string)($newValue ?? ''));
        if ($theirs === $want) continue;                     // already what we want
        if ($theirs !== $ours) {
            // Somebody changed this same field on the server since our last
            // import. Refused rather than merged: there is no way to tell which
            // of the two values is right, and guessing loses one of them.
            $out['conflict'] = true;
            $out['error']    = sprintf(
                'The address book already has a different %s for this contact ("%s"), '
                . 'changed since FreeITSM last read it. Nothing was written. Import again '
                . 'to see their version first.',
                str_replace('_', ' ', $field),
                $theirs === '' ? '(blank)' : $theirs
            );
            cardDavLogWrite($conn, $pid, $userId, $name, 'conflict', [$field], null, '',
                            sprintf('Refused: the card has %s = "%s", FreeITSM last saw "%s", '
                                    . 'and ' . ($fromPortal ? 'the person set, in the self-service portal,' : 'the analyst set') . ' "%s".',
                                    str_replace('_', ' ', $field), $theirs, $ours, $want),
                            $analystId);
            return $out;
        }
        $apply[$field] = $want;
    }
    if (!$apply) {
        $out['ok'] = true; $out['reason'] = 'the card already matched';
        // Logged as skipped rather than ok: nothing was sent, and a log full of
        // successes that wrote nothing would make a broken write-back look busy.
        cardDavLogWrite($conn, $pid, $userId, $name, 'skipped', [], null, '',
                        'The card already matched — nothing to send.', $analystId);
        return $out;
    }

    $edited = cardDavApplyPersonEdits($got['vcard'], $apply);
    if (!$edited['changed']) {
        $out['ok'] = true; $out['reason'] = 'nothing to write';
        cardDavLogWrite($conn, $pid, $userId, $name, 'skipped', [], null, '',
                        'Nothing on the card needed changing.', $analystId);
        return $out;
    }

    // 🔑 The ETag from the GET just done, not the one stored at import. See the
    // docblock: its job here is only to close the GET-to-PUT gap, because the
    // real conflict check has already happened above, per field.
    $etag = $got['etag'] !== '' ? $got['etag'] : (string)$row['source_etag'];
    $put  = cardDavPutCard($cfg, $url, $edited['card'], $etag);

    $out['ok']       = $put['ok'];
    $out['conflict'] = $put['conflict'];
    $out['error']    = $put['error'];
    $out['changed']  = $put['ok'] ? $edited['changed'] : [];

    cardDavLogWrite(
        $conn, $pid, $userId, $name,
        $put['ok'] ? 'ok' : ($put['conflict'] ? 'conflict' : 'failed'),
        $edited['changed'], $put['status'], $put['body'] ?? '',
        $put['ok']
            ? 'Wrote ' . implode(', ', array_map(function ($f) { return str_replace('_', ' ', $f); },
                                                 $edited['changed'])) . ' to the contact card.'
              . ($fromPortal ? ' Changed by the person themselves, in the self-service portal.' : '')
            : $put['error'],
        $analystId
    );

    if ($put['ok']) {
        // Record the new version marker so the next write does not begin by
        // looking stale. Stored as NULL when the server returned none: the
        // distinction is "we have no marker" versus "the marker is blank", and
        // cardDavPutCard() refuses to write without one.
        try {
            $conn->prepare("UPDATE user_sso_identities SET source_etag = ? WHERE id = ?")
                 ->execute([$put['etag'] !== '' ? $put['etag'] : null, (int)$row['identity_id']]);
        } catch (Throwable $e) {
            error_log('[carddav-write] etag update failed: ' . $e->getMessage());
        }
    }
    return $out;
}

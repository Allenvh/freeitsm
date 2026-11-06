<?php
/**
 * CardDAV — ADD a person to an address book (a new card), and link them.
 *
 * For somebody who exists in FreeITSM (an email came in, a ticket was raised)
 * but not in the organisation's address book: an analyst presses "Add to
 * address book" instead of retyping them in another application.
 *
 * ──────────────────────────────────────────────────────────────────────────
 * 🔑 WHY THIS FILE GENERATES A VCARD WHEN carddav_write.php SAYS NEVER TO.
 *
 * That rule is about EXISTING cards: rendering one from FreeITSM's seven fields
 * would silently destroy the photo, notes and every property FreeITSM does not
 * model. A NEW card has none of those - there is nothing to destroy - so it has
 * to be built. Everything that touches an existing card here (the group card,
 * when the source imports a group) still edits the bytes, one line, with
 * If-Match. Kept in its own file so the import path, which must stay provably
 * read-only, never loads it.
 * ──────────────────────────────────────────────────────────────────────────
 *
 * 🔴🔴 THE TRAP THIS IS BUILT AROUND.
 *
 * A source may import only part of its book: one tag, or one group. A card
 * created outside that part is invisible to the next import, which would read
 * the person as having gone - and after `sync_deactivate_after` runs, mark them
 * as left. So:
 *
 *   1. the card is created INSIDE the source's scope: with its tag, or added to
 *      its group;
 *   2. then the book is read back and the import's OWN functions
 *      (cdsyncResolveScope / cdsyncMapCard / cdsyncInScope) are asked whether
 *      the new card would be imported. Not a second copy of the rule - the
 *      rule;
 *   3. if it would not, for any reason, everything written is taken back and
 *      nobody is linked.
 *
 * Duplicates: refused if any card in the book already has the person's email
 * address (the import would adopt that card instead - run it). FreeITSM's own
 * record is LINKED, never copied, and the link records the new card's UID, so
 * the next import finds the person by UID and neither creates nor adopts.
 */

require_once __DIR__ . '/carddav_write.php';      // also loads carddav.php and carddav_sync.php
require_once __DIR__ . '/directory_sync.php';     // dsyncLinkIdentity()

/**
 * Address books this person could be added to right now, and why not if none.
 *
 * @param array $user  the users row - auth_provider_id, email and tenant_id are read
 * @return array ['books' => [provider rows], 'reason' => '' | why nothing is offered]
 */
function cardDavCreateTargets(PDO $conn, array $user): array
{
    $out = ['books' => [], 'reason' => ''];

    // Already linked to a source that still exists: it is theirs, not ours.
    if (!empty($user['auth_provider_id'])) {
        $st = $conn->prepare("SELECT id FROM auth_providers WHERE id = ?");
        $st->execute([(int)$user['auth_provider_id']]);
        if ($st->fetchColumn()) { $out['reason'] = 'linked'; return $out; }
    }
    // The email address is what stops a second card for the same person.
    if (trim((string)($user['email'] ?? '')) === '') { $out['reason'] = 'no_email'; return $out; }

    try {
        $st = $conn->query(
            "SELECT * FROM auth_providers
              WHERE protocol = 'carddav' AND enabled = 1
                AND carddav_write_back = 1 AND carddav_allow_create = 1
                AND carddav_addressbook IS NOT NULL AND carddav_addressbook <> ''
           ORDER BY display_name"
        );
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $p) {
            // A company's address book is for that company's people.
            if ($p['tenant_id'] !== null && (int)$p['tenant_id'] !== (int)($user['tenant_id'] ?? 0)) continue;
            $out['books'][] = $p;
        }
    } catch (Throwable) {
        // Before Database Verification there is no allow-create column: nothing to offer.
    }
    if (!$out['books']) $out['reason'] = 'no_books';
    return $out;
}

/**
 * What the new card would carry, for the confirm step and for the card itself.
 *
 * @param array $user  the users row the card is built from (display_name, email, job_title, department, office, phone, mobile)
 * @param array $provider  the target auth_providers row - its carddav_scope and carddav_scope_value
 */
function cardDavCreatePreview(PDO $conn, array $user, array $provider): array
{
    $scope  = (string)($provider['carddav_scope'] ?? 'all');
    $wanted = cardDavScopeList($provider['carddav_scope_value'] ?? '');
    $fields = [];
    foreach (['display_name', 'email', 'job_title', 'department', 'office', 'phone', 'mobile'] as $f) {
        $v = trim((string)($user[$f] ?? ''));
        if ($v !== '') $fields[$f] = $v;
    }
    return [
        'fields' => $fields,
        'scope'  => $scope,
        // The first tag or group is enough: the import takes a card that has ANY
        // of the chosen ones.
        'scope_value' => ($scope !== 'all' && $wanted) ? $wanted[0] : '',
    ];
}

/** A random RFC 4122 v4 UUID - the card's UID, and its resource name. */
function cardDavNewUid(): string
{
    $b = random_bytes(16);
    $b[6] = chr((ord($b[6]) & 0x0f) | 0x40);
    $b[8] = chr((ord($b[8]) & 0x3f) | 0x80);
    $h = bin2hex($b);
    return substr($h, 0, 8) . '-' . substr($h, 8, 4) . '-' . substr($h, 12, 4) . '-'
         . substr($h, 16, 4) . '-' . substr($h, 20, 12);
}

/**
 * Build the new card. vCard 3.0: what the fixture, Baikal, Nextcloud and iCloud
 * all store, and what cdsyncMapCard() reads.
 *
 * @param string $uid  the new card's UID from cardDavNewUid()
 * @param array $preview  the result of cardDavCreatePreview() - fields, scope, scope_value
 * @param ?string $organisation  the person's company name, written as the first part of ORG; null or '' leaves it empty
 */
function cardDavBuildCard(string $uid, array $preview, ?string $organisation): string
{
    $f = $preview['fields'];
    $e = 'cardDavEscapeText';
    $name  = $f['display_name'] ?? ($f['email'] ?? $uid);
    $words = preg_split('/\s+/', trim($name));
    $family = count($words) > 1 ? array_pop($words) : $name;
    $given  = count($words) >= 1 && $family !== $name ? implode(' ', $words) : '';

    $lines = [
        'BEGIN:VCARD',
        'VERSION:3.0',
        'PRODID:-//FreeITSM//EN',
        'UID:' . $uid,
        'FN:' . $e($name),
        'N:' . $e($family) . ';' . $e($given) . ';;;',
    ];
    if (isset($f['email']))     $lines[] = 'EMAIL;TYPE=INTERNET,WORK:' . $e($f['email']);
    if (isset($f['phone']))     $lines[] = 'TEL;TYPE=WORK,VOICE:' . $e($f['phone']);
    if (isset($f['mobile']))    $lines[] = 'TEL;TYPE=CELL:' . $e($f['mobile']);
    if (isset($f['job_title'])) $lines[] = 'TITLE:' . $e($f['job_title']);
    // ORG: organisation;department - cdsyncOrg() reads the second part.
    if (isset($f['department']) || ($organisation ?? '') !== '') {
        $lines[] = 'ORG:' . $e((string)$organisation) . (isset($f['department']) ? ';' . $e($f['department']) : '');
    }
    // ADR's fourth component is the locality, which is what cdsyncLocality() reads.
    if (isset($f['office']))    $lines[] = 'ADR;TYPE=WORK:;;;' . $e($f['office']) . ';;;';
    if ($preview['scope'] === 'category' && $preview['scope_value'] !== '') {
        $lines[] = 'CATEGORIES:' . $e($preview['scope_value']);
    }
    $lines[] = 'REV:' . gmdate('Ymd\THis\Z');
    $lines[] = 'END:VCARD';
    return implode("\r\n", array_map('cardDavFoldLine', $lines)) . "\r\n";
}

/**
 * Find a card in a fetched book by UID.
 *
 * @param array $cards  the 'cards' list from cardDavFetchCards() - each with href, etag, vcard
 * @param string $uid  the UID to find, matched exactly
 */
function cardDavFindCardByUid(array $cards, string $uid): ?array
{
    foreach ($cards as $c) {
        $lines = cardDavUnfold($c['vcard']);
        if (trim(cardDavProperty($lines, 'UID')[0] ?? '') === $uid) return $c;
    }
    return null;
}

/**
 * Add a person to an address book and link them to it.
 *
 * @param int $userId  users.id of the person to add
 * @param int $providerId  auth_providers.id of the address book to add them to; must be one cardDavCreateTargets() offers
 * @return array ['ok'=>bool, 'error'=>string, 'code'=>string, 'existing'=>string]
 *   code: '' | not_allowed | duplicate | failed | out_of_scope
 */
function cardDavCreateContact(PDO $conn, int $userId, int $providerId, ?int $analystId): array
{
    $out = ['ok' => false, 'error' => '', 'code' => '', 'existing' => ''];

    $u = $conn->prepare(
        "SELECT u.*, t.name AS tenant_name FROM users u
      LEFT JOIN tenants t ON t.id = u.tenant_id WHERE u.id = ?"
    );
    $u->execute([$userId]);
    $user = $u->fetch(PDO::FETCH_ASSOC);
    if (!$user) { $out['code'] = 'not_allowed'; $out['error'] = 'That person no longer exists.'; return $out; }

    // Every rule that decides whether the button is offered, applied again here:
    // the button is a convenience, never the guard.
    $targets = cardDavCreateTargets($conn, $user);
    $provider = null;
    foreach ($targets['books'] as $b) if ((int)$b['id'] === $providerId) $provider = $b;
    if (!$provider) {
        $out['code'] = 'not_allowed';
        $out['error'] = 'This person cannot be added to that address book.';
        return $out;
    }

    $cfg = cardDavConfigFromProvider($provider);
    $cfg['interactive'] = true;
    $bookUrl = rtrim(cardDavAbsoluteUrl($cfg['url'], (string)$provider['carddav_addressbook']), '/') . '/';
    $email   = strtolower(trim((string)$user['email']));
    $name    = (string)($user['display_name'] ?: $user['email']);
    $log = function (string $outcome, array $fields, ?int $status, string $body, string $msg)
               use ($conn, $providerId, $userId, $name, $analystId) {
        cardDavLogWrite($conn, $providerId, $userId, $name, $outcome, $fields, $status, $body, $msg, $analystId);
    };

    // --- 1. read the book: duplicates, and the group card if the scope is a group ---
    $book = cardDavFetchCards($cfg, (string)$provider['carddav_addressbook']);
    if (!$book['ok']) {
        $out['code'] = 'failed';
        $out['error'] = 'The address book could not be read: ' . ($book['error'] ?: cardDavExplainStatus((int)$book['status']));
        $log('failed', [], (int)$book['status'] ?: null, '', 'Add refused before writing: ' . $out['error']);
        return $out;
    }
    foreach ($book['cards'] as $c) {
        $lines = cardDavUnfold($c['vcard']);
        if (strtolower(trim(cardDavProperty($lines, 'KIND')[0] ?? '')) === 'group') continue;
        foreach (cardDavProperty($lines, 'EMAIL') as $em) {
            if (strtolower(trim($em)) === $email) {
                $out['code'] = 'duplicate';
                $out['existing'] = trim(cardDavUnescapeText(cardDavProperty($lines, 'FN')[0] ?? '')) ?: $email;
                $out['error'] = 'The address book already has a contact with this email address ('
                              . $out['existing'] . '). Nothing was written. Run the import to link them instead.';
                $log('skipped', [], null, '', 'Add refused: ' . $email . ' is already on the card for ' . $out['existing'] . '.');
                return $out;
            }
        }
    }

    $preview = cardDavCreatePreview($conn, $user, $provider);
    $group = null;
    if ($preview['scope'] === 'group') {
        $want = mb_strtolower($preview['scope_value']);
        foreach ($book['cards'] as $c) {
            $lines = cardDavUnfold($c['vcard']);
            if (strtolower(trim(cardDavProperty($lines, 'KIND')[0] ?? '')) !== 'group') continue;
            // The same either-or match the import uses: UID, or the group's name.
            if (mb_strtolower(trim(cardDavProperty($lines, 'UID')[0] ?? '')) === $want
                || mb_strtolower(trim(cardDavProperty($lines, 'FN')[0] ?? '')) === $want) { $group = $c; break; }
        }
        if (!$group) {
            $out['code'] = 'out_of_scope';
            $out['error'] = 'This address book imports only a group that FreeITSM cannot find, so the person was not added.';
            $log('failed', [], null, '', 'Add refused before writing: ' . $out['error']);
            return $out;
        }
    }

    // --- 2. create the card, never over an existing one ---
    $uid = cardDavNewUid();
    $cardUrl = $bookUrl . $uid . '.vcf';
    $vcard = cardDavBuildCard($uid, $preview, $user['tenant_name'] ?? null);
    $put = cardDavRequest($cfg, 'PUT', $cardUrl, $vcard, [
        'Content-Type: text/vcard; charset=utf-8',
        'If-None-Match: *',
    ]);
    if (!$put['ok']) {
        $out['code'] = 'failed';
        $out['error'] = 'The address book did not accept the new contact: '
                      . ($put['error'] ?: cardDavExplainStatus((int)$put['status']));
        $log('failed', [], (int)$put['status'] ?: null, $put['body'] ?? '', $out['error']);
        return $out;
    }

    // Everything written so far, so any later failure can take it back.
    $undo = function () use ($cfg, $cardUrl) {
        cardDavRequest($cfg, 'DELETE', $cardUrl);
    };
    $memberLine = 'MEMBER:urn:uuid:' . $uid;
    $groupUrl = null;

    // --- 3. into the group, by editing the group card's bytes ---
    if ($group) {
        $groupUrl = cardDavAbsoluteUrl($cfg['url'], $group['href']);
        $added = false;
        for ($attempt = 0; $attempt < 2 && !$added; $attempt++) {
            $got = $attempt === 0 ? ['ok' => true, 'vcard' => $group['vcard'], 'etag' => $group['etag']]
                                  : cardDavGetCard($cfg, $groupUrl);
            if (!$got['ok']) break;
            $eol = cardDavDetectEol($got['vcard']);
            $pos = strripos($got['vcard'], 'END:VCARD');
            if ($pos === false) break;
            $edited = substr($got['vcard'], 0, $pos) . $memberLine . $eol . substr($got['vcard'], $pos);
            $gp = cardDavPutCard($cfg, $groupUrl, $edited, (string)$got['etag']);
            $added = $gp['ok'];
            if (!$added && !$gp['conflict']) break;   // a 412 is worth one re-read
        }
        if (!$added) {
            $undo();
            $out['code'] = 'failed';
            $out['error'] = 'The contact could not be added to the group this address book imports, so it was removed again. Nothing changed.';
            $log('failed', [], null, '', $out['error']);
            return $out;
        }
    }

    // --- 4. ask the import's own rules whether it will see this card ---
    $after = cardDavFetchCards($cfg, (string)$provider['carddav_addressbook']);
    $card  = $after['ok'] ? cardDavFindCardByUid($after['cards'], $uid) : null;
    $mapped = $card ? cdsyncMapCard($card['vcard'], $card['href']) : null;
    $scopeWanted = cardDavScopeList($provider['carddav_scope_value'] ?? '');
    $resolved = $after['ok'] ? cdsyncResolveScope($after['cards'], $preview['scope'], $scopeWanted) : null;
    // Exactly the import's test, including its reading of "no tag chosen" as
    // "everything" (cdsyncResolveScope() returns null for both).
    $visible = $card && $mapped && cdsyncInScope($resolved, $mapped);
    if (!$visible) {
        if ($group && $groupUrl) {
            $g = cardDavGetCard($cfg, $groupUrl);
            if ($g['ok']) {
                $eol = cardDavDetectEol($g['vcard']);
                $clean = str_replace($memberLine . $eol, '', $g['vcard']);
                if ($clean !== $g['vcard']) cardDavPutCard($cfg, $groupUrl, $clean, (string)$g['etag']);
            }
        }
        $undo();
        $out['code'] = 'out_of_scope';
        $out['error'] = 'The new contact would not have been picked up by this address book\'s import, so it was removed again and nothing was linked.';
        $log('failed', [], null, '', $out['error']);
        return $out;
    }

    // --- 5. link FreeITSM's own record - the same record, never a copy ---
    $conn->prepare(
        "UPDATE users SET is_managed = 1, auth_provider_id = ?, last_seen_in_source = UTC_TIMESTAMP(),
                          sync_missed_count = 0
          WHERE id = ?"
    )->execute([$providerId, $userId]);
    $p = $mapped;
    $p['carddav_etag'] = $card['etag'] ?? '';
    dsyncLinkIdentity($conn, $providerId, $userId, $p);

    $log('created', array_keys($preview['fields']), (int)$put['status'], '',
         'Added to the address book as a new contact'
         . ($preview['scope'] === 'category' ? ', tagged "' . $preview['scope_value'] . '"' : '')
         . ($preview['scope'] === 'group' ? ', in the group "' . $preview['scope_value'] . '"' : '')
         . ', and linked.');
    $out['ok'] = true;
    return $out;
}

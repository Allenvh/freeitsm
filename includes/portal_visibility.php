<?php
/**
 * What the requester may see of their OWN ticket in the self-service portal.
 *
 * THE PROBLEM
 * -----------
 * A ticket's thread is not always a conversation with the requester. When an
 * analyst forwards a ticket to a supplier, or CCs a colleague, that message is
 * written onto the SAME ticket — and the supplier's reply lands there too. The
 * portal showed the whole thread, so correspondence *about* the requester was
 * visible *to* the requester, occasionally with another customer's document
 * attached.
 *
 * "Forward" is not recorded anywhere: a forward and an ordinary reply look
 * identical in `emails`. So the rule is not "was this a forward" but the
 * question that actually matters, which also covers CCs and third-party
 * replies, and works on existing history with no migration:
 *
 *     Was this message TO or FROM the person who raised the ticket?
 *
 * WHERE THIS MUST BE APPLIED
 * --------------------------
 * BOTH the thread listing AND the attachment download endpoint. Hiding a
 * message in the list while its attachment URL still works is decoration, not
 * enforcement — the id is guessable and the file is the sensitive part.
 *
 * FAILING TOWARDS VISIBILITY
 * --------------------------
 * Every ambiguous case resolves to VISIBLE, deliberately:
 *
 *   - Non-email channels (WhatsApp, web chat) are addressed by phone number or
 *     visitor id, so a requester's EMAIL matches nothing on them. Comparing
 *     addresses there would hide the customer's entire conversation from their
 *     own portal. There is also no "forward" on those channels — the exchange is
 *     between the visitor and the desk by definition. Always visible.
 *   - A message with no recipients recorded cannot be judged. Visible.
 *   - A requester whose address is simply UNKNOWN to us ('') cannot be judged.
 *     Visible.
 *
 * Hiding an analyst's genuine reply is a worse failure than showing a forward:
 * the customer is left believing nobody answered them. The strict direction is
 * the one an admin opts into per-message-type, not one we guess at.
 *
 * ⚠️ THE ONE CASE THAT IS NOT AMBIGUOUS
 * -------------------------------------
 * A requester who has NO MAILBOX AT ALL (`$requesterEmail === null`) is a
 * different thing entirely from one whose address we don't know. Directory
 * users may genuinely have none (GitHub #47). No email can ever have been
 * addressed to them, so every email-channel message on their ticket is
 * third-party by definition and the policy APPLIES — it is not ambiguous and
 * must not fail open. Their own messages arrive by the portal and are matched
 * on `direction` well before any address comparison.
 */

require_once __DIR__ . '/functions.php';

/** Third-party messages are hidden from the portal entirely (the default). */
const PORTAL_THIRD_PARTY_HIDE = 'hide';
/** The message shows, but its attachments are withheld. */
const PORTAL_THIRD_PARTY_NO_ATTACHMENTS = 'no_attachments';
/** Everything on the ticket is shown — the behaviour before this setting existed. */
const PORTAL_THIRD_PARTY_SHOW = 'show';

/**
 * The configured policy: Tickets → Settings → Privacy.
 *
 * Defaults to HIDE when never saved. Ed's explicit call: showing a supplier
 * forward to the customer is treated as the bug it is, so a fresh or upgrading
 * install is protected without anyone having to find the setting first.
 */
function portalThirdPartyPolicy(PDO $conn): string {
    static $cached = null;
    if ($cached !== null) return $cached;

    $valid = [PORTAL_THIRD_PARTY_HIDE, PORTAL_THIRD_PARTY_NO_ATTACHMENTS, PORTAL_THIRD_PARTY_SHOW];
    try {
        $stmt = $conn->prepare("SELECT setting_value FROM system_settings WHERE setting_key = 'portal_third_party_visibility'");
        $stmt->execute();
        $val = $stmt->fetchColumn();
        $cached = ($val !== false && in_array($val, $valid, true)) ? $val : PORTAL_THIRD_PARTY_HIDE;
    } catch (Exception) {
        $cached = PORTAL_THIRD_PARTY_HIDE;   // unreadable settings → protect.
    }
    return $cached;
}

/**
 * Is this message part of the requester's own correspondence?
 *
 * @param array  $email           needs: channel, direction, from_address,
 *                                to_recipients, cc_recipients
 * @param ?string $requesterEmail the ticket owner's address. NULL = they have
 *                                NO mailbox (a directory user); '' = unknown.
 *                                The two mean opposite things here.
 * @return bool true = the requester's own conversation (always portal-visible)
 */
function portalEmailInvolvesRequester(array $email, ?string $requesterEmail): bool {
    // Non-email channels: addressed by phone number / visitor id, never by the
    // requester's email. Judging them by address would hide their own chat.
    $channel = strtolower(trim((string)($email['channel'] ?? 'email')));
    if ($channel !== '' && $channel !== 'email') return true;

    // The requester typed it in the portal themselves.
    if (strtolower(trim((string)($email['direction'] ?? ''))) === 'portal') return true;

    // 🔴 "This person HAS no address" is not the same as "we don't know their
    // address", and conflating the two silently defeats the whole privacy
    // policy. NULL means the former; '' means the latter.
    //
    // Directory users may genuinely have no mailbox (GitHub #47). For them an
    // EMAIL-channel message cannot possibly be to or from them — there is no
    // address it could have been sent to. So every email on their ticket is
    // third-party correspondence by definition and the configured policy must
    // apply. Treating it as "involves them", which the old `$needle === ''`
    // branch did, would show a mailbox-less requester every forward to a
    // supplier and every copied-in colleague's reply — precisely the disclosure
    // #892 exists to prevent, aimed at the people least likely to notice.
    //
    // Their own conversation is already covered above: what they typed returns
    // at the `direction === 'portal'` check, and anything an analyst chooses to
    // share with them is a NOTE, which never reaches this function.
    if ($requesterEmail === null) return false;

    $needle = strtolower(trim($requesterEmail));
    if ($needle === '' || !str_contains($needle, '@')) return true;   // can't judge → visible

    $to = trim((string)($email['to_recipients'] ?? ''));
    $cc = trim((string)($email['cc_recipients'] ?? ''));
    $from = trim((string)($email['from_address'] ?? ''));

    // Nothing recorded to compare against → visible rather than wrongly hidden.
    if ($to === '' && $cc === '' && $from === '') return true;

    // 🔴 The requester's address must BE one of the addresses, not appear inside
    // one. A substring test made bob@acme.com part of every message to or from
    // jimbob@acme.com or bob@acme.com.au - so with the privacy setting on, a
    // requester saw exactly the third-party mail and attachments it exists to
    // withhold. Reported privately, October 2026.
    return in_array($needle, array_merge(portalAddressSet($from), portalAddressSet($to), portalAddressSet($cc)), true);
}

/**
 * Every email address in a stored from / to / cc value, lower-cased.
 *
 * Copes with every shape the columns hold: one bare address; a list joined with
 * "; " or "," (inbound mail, and replies as an analyst typed them); "Name <a@b>";
 * and the JSON a few older rows carry ([{"emailAddress":{"address":...}}]).
 *
 * @param string $raw  the column's value
 * @return string[]
 */
function portalAddressSet(string $raw): array {
    $raw = trim($raw);
    if ($raw === '') return [];
    $found = [];
    if ($raw[0] === '[' || $raw[0] === '{') {
        $json = json_decode($raw, true);
        if (is_array($json)) {
            array_walk_recursive($json, function ($v, $k) use (&$found) {
                if (is_string($v) && in_array($k, ['address', 'email'], true)) $found[] = $v;
            });
        }
    }
    if (!$found && preg_match_all("/[A-Z0-9._%+'-]+@[A-Z0-9.-]+/i", $raw, $m)) $found = $m[0];
    return array_values(array_unique(array_map(fn($a) => strtolower(trim($a, " .<>	")), $found)));
}

/**
 * Apply the policy to one message.
 *
 * @param array $email  the emails row - needs channel, direction, from_address, to_recipients, cc_recipients
 * @param ?string $requesterEmail  the ticket owner's address; null = they have no mailbox, '' = unknown
 * @param string $policy  the third-party policy: 'hide', 'no_attachments' or 'show' (PORTAL_THIRD_PARTY_*); anything else hides
 * @return array{visible: bool, attachments: bool}
 *         visible     — show the message in the thread at all
 *         attachments — allow its attachments to be listed AND downloaded
 */
function portalEmailVisibility(array $email, ?string $requesterEmail, string $policy): array {
    if (portalEmailInvolvesRequester($email, $requesterEmail)) {
        return ['visible' => true, 'attachments' => true];
    }

    switch ($policy) {
        case PORTAL_THIRD_PARTY_SHOW:
            return ['visible' => true,  'attachments' => true];
        case PORTAL_THIRD_PARTY_NO_ATTACHMENTS:
            return ['visible' => true,  'attachments' => false];
        case PORTAL_THIRD_PARTY_HIDE:
        default:
            return ['visible' => false, 'attachments' => false];
    }
}

/**
 * The list-pane preview for each ticket: the first 160 characters of the newest
 * message the requester is allowed to see, by the same rule as the ticket itself.
 *
 * 🔴 The portal lists (get_tickets.php, get_team_tickets.php) used to take the
 * newest message whatever it was, straight from SQL. With the privacy setting on
 * "hide", a supplier's reply was withheld from the ticket but its opening lines
 * were still printed under the ticket's subject in the list. Reported privately,
 * October 2026. The rule is applied against the TICKET'S requester, as the detail
 * view does, so a manager's team list shows exactly what the requester would see.
 *
 * @param int[] $ticketIds  the tickets on this page of the list
 * @return array<int, string>  ticket id => preview; a ticket with nothing visible is absent
 */
function portalVisiblePreviews(PDO $conn, array $ticketIds): array {
    $ticketIds = array_values(array_unique(array_map('intval', $ticketIds)));
    if (!$ticketIds) return [];
    $policy = portalThirdPartyPolicy($conn);
    $ph = implode(',', array_fill(0, count($ticketIds), '?'));
    $stmt = $conn->prepare(
        "SELECT e.ticket_id, LEFT(e.body_preview, 160) AS preview,
                e.channel, e.direction, e.from_address, e.to_recipients, e.cc_recipients,
                u.email AS requester_email
           FROM emails e
           JOIN tickets t     ON t.id = e.ticket_id
      LEFT JOIN users u       ON u.id = t.user_id
          WHERE e.ticket_id IN ($ph)
       ORDER BY e.ticket_id, e.received_datetime DESC, e.id DESC"
    );
    $stmt->execute($ticketIds);
    $out = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $m) {
        $tid = (int)$m['ticket_id'];
        if (array_key_exists($tid, $out)) continue;   // already have this ticket's newest visible one
        // NULL (no user, or no address) and '' mean different things - see portalEmailInvolvesRequester().
        $req = $m['requester_email'] === null ? null : (string)$m['requester_email'];
        if (portalEmailVisibility($m, $req, $policy)['visible']) $out[$tid] = (string)$m['preview'];
    }
    return $out;
}

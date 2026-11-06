<?php
/**
 * Calendar sync — loading connections, choosing a provider, and reading an
 * analyst's enrolment (GH discussion #75).
 *
 * The core of the feature lives above the provider contract in
 * CalendarSyncProvider.php: this file is the plumbing that gets a usable
 * provider object and answers "does this analyst want their scheduled work in
 * their calendar, and where".
 *
 * Credential handling deliberately mirrors includes/integrations/integrations.php
 * — an encrypted JSON blob in a `credentials` column, decoded on load, and never
 * returned to a UI. One convention for secrets, not two.
 */

require_once __DIR__ . '/CalendarSyncProvider.php';
require_once __DIR__ . '/../encryption.php';

/** Modes an analyst can be in. ONE of these, never a combination — see below. */
const CALENDAR_MODE_OFF  = 'off';
const CALENDAR_MODE_PUSH = 'push';   // real events written into their calendar
const CALENDAR_MODE_FEED = 'feed';   // a subscribe (.ics) link they add themselves

/**
 * 🔑 WHY MODE IS ONE VALUE AND NOT TWO BOOLEANS.
 *
 * With a push AND a subscribed feed both live, every scheduled ticket appears
 * TWICE in the same calendar — once as a real event and once from the
 * subscription. Independent switches make that trivially easy to do by accident
 * and baffling to diagnose. A single choice makes it unrepresentable.
 *
 * @param string $mode  a stored or submitted calendar_enrolments.mode value - valid ones are off, push and feed
 */
function calendarModeIsValid(string $mode): bool
{
    return in_array($mode, [CALENDAR_MODE_OFF, CALENDAR_MODE_PUSH, CALENDAR_MODE_FEED], true);
}

// ─── What of a TASK reaches the calendar (#75) ──────────────────────────────
//
// 🔑 A SECOND VALUE, NOT MORE VALUES ON `mode`. `mode` decides HOW things get
// there — a push, a subscribed feed, or nothing. This decides WHAT. They are
// independent: folding them together would need eight values to say two things,
// and switching tickets off would silently take tasks with them.
//
// ⚠️ A task has TWO datable things and they are not the same kind of fact:
//   - the scheduled work window, which is a plan — when you intend to do it;
//   - the due date, which is a commitment — when it must be finished by.
// Tickets only have the first, which is why this choice does not exist there.
// On a real install the second is the one people actually fill in, so syncing
// only the work window would put nothing in anybody's calendar.
const TASK_CAL_OFF  = 'off';
const TASK_CAL_WORK = 'work';   // the scheduled work window, as a timed appointment
const TASK_CAL_DUE  = 'due';    // the due date, as an all-day event
const TASK_CAL_BOTH = 'both';

/**
 * True when $mode is one of the task calendar modes - off, work, due or both.
 *
 * @param string $mode  a stored or submitted calendar_enrolments.task_mode value
 */
function taskCalendarModeIsValid(string $mode): bool
{
    return in_array($mode, [TASK_CAL_OFF, TASK_CAL_WORK, TASK_CAL_DUE, TASK_CAL_BOTH], true);
}

/**
 * Does this analyst want a task's work window in their calendar?
 *
 * @param string $mode  the analyst's task mode (TASK_CAL_*), already checked by taskCalendarModeIsValid()
 */
function taskCalendarWantsWork(string $mode): bool
{
    return $mode === TASK_CAL_WORK || $mode === TASK_CAL_BOTH;
}

/**
 * Does this analyst want a task's due date in their calendar?
 *
 * @param string $mode  the analyst's task mode (TASK_CAL_*), already checked by taskCalendarModeIsValid()
 */
function taskCalendarWantsDue(string $mode): bool
{
    return $mode === TASK_CAL_DUE || $mode === TASK_CAL_BOTH;
}

/**
 * Decrypt + JSON-decode a stored credentials blob (never throws).
 *
 * @param mixed $stored  the encrypted credentials column as read from the database; null or '' gives []
 */
function calendarSyncDecodeCredentials($stored): array
{
    if ($stored === null || $stored === '') return [];
    try {
        $plain = decryptValue($stored);
    } catch (Exception) {
        return [];
    }
    $decoded = json_decode((string) $plain, true);
    return is_array($decoded) ? $decoded : [];
}

/**
 * Encrypt a credentials array for storage.
 *
 * @param array $credentials  the secrets to store, e.g. ['username' => ..., 'password' => ...]
 */
function calendarSyncEncodeCredentials(array $credentials): string
{
    return encryptValue(json_encode($credentials));
}

/**
 * Does this database have the calendar-sync tables yet?
 *
 * Same guard, and the same reasoning, as scheduleSchemaReady(): an install that
 * has pulled this code but not run Database Verification must simply not offer
 * the feature, rather than throwing "table doesn't exist" at somebody trying to
 * schedule a ticket. The push hangs off the schedule write path, which is
 * everyday work and must never be taken down by an unrelated missing table.
 */
function calendarSyncSchemaReady(PDO $conn): bool
{
    static $ready = null;
    if ($ready !== null) return $ready;
    try {
        $ready = (bool)$conn->query("SHOW TABLES LIKE 'calendar_enrolments'")->fetch(PDO::FETCH_NUM);
    } catch (Exception) {
        $ready = false;
    }
    return $ready;
}

/**
 * Load a connection with its secrets decrypted, or null.
 *
 * A connection may BORROW an Azure app registration already configured for a
 * mailbox (calendar_connections.mailbox_id) instead of carrying its own —
 * most installs have already done that setup for mail, and adding one Graph
 * permission to an existing app is far less work than registering a second one.
 * Resolving that here means providers never have to know about it.
 *
 * @param int $connectionId  calendar_connections.id; an inactive or missing one gives null
 */
function calendarSyncLoadConnection(PDO $conn, int $connectionId): ?array
{
    $stmt = $conn->prepare("SELECT * FROM calendar_connections WHERE id = ? AND is_active = 1");
    $stmt->execute([$connectionId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) return null;

    $row['credentials'] = calendarSyncDecodeCredentials($row['credentials'] ?? null);

    if (empty($row['credentials']) && !empty($row['mailbox_id'])) {
        $mStmt = $conn->prepare("SELECT * FROM target_mailboxes WHERE id = ? AND is_active = 1");
        $mStmt->execute([(int)$row['mailbox_id']]);
        $mailbox = $mStmt->fetch(PDO::FETCH_ASSOC);
        if ($mailbox) {
            $mailbox = decryptMailboxRow($mailbox);
            $row['credentials'] = [
                'tenant_id'     => $mailbox['azure_tenant_id']     ?? '',
                'client_id'     => $mailbox['azure_client_id']     ?? '',
                'client_secret' => $mailbox['azure_client_secret'] ?? '',
            ];
            $row['borrowed_from_mailbox'] = $mailbox['name'] ?? '';
        }
    }

    return $row;
}

// ─── Which connection ────────────────────────────────────────────────────────
//
// 🔑 ANY NUMBER OF CONNECTIONS, AND EACH ANALYST USES ONE. An organisation can
// have most people on Microsoft 365 and a few on Nextcloud, or an MSP several
// tenants, or one person on iCloud. So there is no "the" connection: the
// analyst's enrolment names theirs (calendar_enrolments.connection_id), and
// every event remembers the one that wrote it (calendar_sync_events.connection_id).

/** The ids of every active connection, oldest first. */
function calendarSyncConnectionIds(PDO $conn): array
{
    if (!calendarSyncSchemaReady($conn)) return [];
    try {
        return array_map('intval', $conn->query(
            "SELECT id FROM calendar_connections WHERE is_active = 1 ORDER BY id"
        )->fetchAll(PDO::FETCH_COLUMN));
    } catch (Exception) {
        return [];
    }
}

/**
 * The connection when the install has exactly one, else null.
 *
 * Only a fallback now: it keeps an enrolment that names no connection working
 * on a single-connection install - one made before connections could be
 * chosen, or left behind when its connection was deleted and a new one added -
 * without ever guessing between two.
 */
function calendarSyncOnlyConnection(PDO $conn): ?array
{
    $ids = calendarSyncConnectionIds($conn);
    return count($ids) === 1 ? calendarSyncLoadConnection($conn, $ids[0]) : null;
}

/**
 * The connection this analyst's calendar work goes through, or null.
 *
 * @param array $enrolment  the analyst's calendar_enrolments row as calendarSyncEnrolment() returns it
 */
function calendarSyncConnectionFor(PDO $conn, array $enrolment): ?array
{
    if (!empty($enrolment['connection_id'])) {
        return calendarSyncLoadConnection($conn, (int)$enrolment['connection_id']);
    }
    return calendarSyncOnlyConnection($conn);
}

/**
 * A connection's provider name, or '' when it does not exist. Cached per
 * request, because it is asked for every enrolment that is read.
 *
 * @param ?int $connectionId  calendar_connections.id; null or 0 gives ''
 */
function calendarSyncConnectionProvider(PDO $conn, ?int $connectionId): string
{
    static $cache = [];
    if (!$connectionId) return '';
    if (!array_key_exists($connectionId, $cache)) {
        try {
            $st = $conn->prepare("SELECT provider FROM calendar_connections WHERE id = ? AND is_active = 1");
            $st->execute([$connectionId]);
            $cache[$connectionId] = (string)($st->fetchColumn() ?: '');
        } catch (Exception) {
            $cache[$connectionId] = '';
        }
    }
    return $cache[$connectionId];
}

/**
 * Build the provider for a connection.
 *
 * ⚠️ Throws on an unknown provider rather than returning null. A connection row
 * naming a provider this build does not have is a misconfiguration, and pushing
 * silently to nowhere is the worst possible response to it.
 *
 * @param array $connection  a calendar_connections row with credentials decrypted, from calendarSyncLoadConnection()
 * @param array|null $enrolment the enrolment of the analyst whose calendar is
 *        being touched. Microsoft ignores it; CalDAV signs in with the
 *        credentials on it (see CalendarSyncProvider::$account). Pass it
 *        whenever an analyst is involved - a CalDAV provider built without one
 *        refuses every call with an explanation rather than guessing.
 */
function calendarSyncProviderFor(array $connection, ?array $enrolment = null): CalendarSyncProvider
{
    switch ($connection['provider'] ?? '') {
        case 'microsoft':
            require_once __DIR__ . '/MicrosoftCalendarProvider.php';
            return new MicrosoftCalendarProvider($connection);
        case 'caldav':
            require_once __DIR__ . '/CalDavCalendarProvider.php';
            $p = new CalDavCalendarProvider($connection);
            return $enrolment ? $p->withAccount(calendarSyncAccount($enrolment)) : $p;
        default:
            throw new Exception('Unknown calendar provider: ' . ($connection['provider'] ?? '?'));
    }
}

/** Providers FreeITSM can use. The list the admin screen offers and the save accepts. */
const CALENDAR_PROVIDERS = ['microsoft', 'caldav'];

/**
 * An analyst's own calendar sign-in, decrypted: ['username' => …, 'password' => …].
 * Empty when they have not saved one. Never returned to a browser.
 *
 * @param array $enrolment  a calendar_enrolments row - its encrypted 'credentials' column is read
 */
function calendarSyncAccount(array $enrolment): array
{
    $creds = calendarSyncDecodeCredentials($enrolment['credentials'] ?? null);
    return [
        'username' => (string)($creds['username'] ?? ''),
        'password' => (string)($creds['password'] ?? ''),
    ];
}

/**
 * One analyst's enrolment, or a synthetic "off" row when they have never chosen.
 *
 * ⚠️ NEVER returns null for a real analyst. A missing row means "has not decided
 * yet", which is off — and a caller forced to distinguish those two would sooner
 * or later treat one as the other.
 *
 * calendar_address falls back to analysts.email, which is what it is for nearly
 * everyone; the column exists for the analyst whose login address is not their
 * mailbox.
 */
function calendarSyncEnrolment(PDO $conn, int $analystId): array
{
    // 🔴 THE ADDRESS IS RESOLVED FOR THE "never chose" CASE TOO, which is
    // everyone until they opt in. Deriving it only when an enrolment row already
    // existed left the settings screen with nothing to show as "we will write
    // to…" for every analyst who had not enrolled yet — i.e. all of them, at
    // exactly the moment they are deciding whether to.
    $email = null;
    try {
        $aStmt = $conn->prepare("SELECT email FROM analysts WHERE id = ?");
        $aStmt->execute([$analystId]);
        $email = $aStmt->fetchColumn() ?: null;
    } catch (Exception) {
        // Falls through as null — an unknown address is reported honestly rather
        // than the caller being handed a guess.
    }
    // 🔴 NOT FOR CALDAV. There the address is the web address of a calendar the
    // analyst chose themselves, and an email address in its place would be a
    // target that looks set and can never work. Until they choose one, there is
    // none. Decided by the analyst's OWN connection - or, when they have none
    // yet, by the install's only one.
    $isCalDav = function (?array $row) use ($conn): bool {
        $cid = $row && !empty($row['connection_id']) ? (int)$row['connection_id'] : null;
        if ($cid) return calendarSyncConnectionProvider($conn, $cid) === 'caldav';
        $ids = calendarSyncConnectionIds($conn);
        return count($ids) === 1 && calendarSyncConnectionProvider($conn, $ids[0]) === 'caldav';
    };

    $off = [
        'analyst_id'       => $analystId,
        'mode'             => CALENDAR_MODE_OFF,
        'task_mode'        => TASK_CAL_OFF,
        'connection_id'    => null,
        'calendar_address' => $isCalDav(null) ? null : $email,
        'last_error'       => null,
    ];
    if (!calendarSyncSchemaReady($conn)) return $off;

    try {
        $stmt = $conn->prepare("SELECT * FROM calendar_enrolments WHERE analyst_id = ?");
        $stmt->execute([$analystId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception) {
        return $off;
    }
    if (!$row) return $off;

    if (empty($row['calendar_address'])) {
        $row['calendar_address'] = $isCalDav($row) ? null : $email;
    }
    if (!calendarModeIsValid((string)$row['mode'])) {
        $row['mode'] = CALENDAR_MODE_OFF;      // a value we do not recognise is not a licence to push
    }
    // Same rule for tasks, and for the same reason. An install that has not run
    // Database Verification yet has no task_mode column at all, so the key may
    // be absent rather than merely wrong — both land on 'off'.
    if (!taskCalendarModeIsValid((string)($row['task_mode'] ?? ''))) {
        $row['task_mode'] = TASK_CAL_OFF;
    }
    return $row;
}

// ─── Subscribe (.ics) feeds ─────────────────────────────────────────────────
//
// 🔑 THE FEED IS DELIBERATELY NOT TIED TO A CONNECTION. An install with no
// supported calendar provider — no Azure, no Google Workspace — must still be
// able to publish one, because it is the only integration those installs get.
// The policy therefore lives in system_settings, not on calendar_connections.
// (An earlier draft put an allow_feed column on the connection, which quietly
// meant "no Microsoft, no feed" — exactly backwards.)

/** system_settings key holding the install-wide ceiling. */
const SCHEDULE_FEED_SETTING = 'tickets_schedule_feed_mode';

const FEED_MODE_OFF  = 'off';    // no subscribe links at all on this install
const FEED_MODE_REF  = 'ref';    // allowed, but ticket NUMBERS only — no subjects
const FEED_MODE_FULL = 'full';   // allowed, and the analyst chooses the detail

/**
 * The install-wide ceiling on subscribe links.
 *
 * Defaults to 'full' — permissive — because nothing is actually published until
 * an analyst deliberately mints a link for themselves. An upgrade therefore
 * exposes nothing on its own, and an administrator who wants the feature gone
 * turns it off without anyone having had to opt in first.
 */
function scheduleFeedMode(PDO $conn): string
{
    static $mode = null;
    if ($mode !== null) return $mode;
    $mode = FEED_MODE_FULL;
    try {
        $stmt = $conn->prepare("SELECT setting_value FROM system_settings WHERE setting_key = ?");
        $stmt->execute([SCHEDULE_FEED_SETTING]);
        $v = $stmt->fetchColumn();
        if (in_array($v, [FEED_MODE_OFF, FEED_MODE_REF, FEED_MODE_FULL], true)) $mode = $v;
    } catch (Exception) {
        // Setting table unreadable — the permissive default stands, which is the
        // same state as an install that has never touched this.
    }
    return $mode;
}

/** May anyone subscribe on this install? */
function scheduleFeedAllowed(PDO $conn): bool
{
    return scheduleFeedMode($conn) !== FEED_MODE_OFF;
}

/**
 * How much detail THIS analyst's feed carries: 'full' or 'ref'.
 *
 * 🔑 THE ORGANISATION SETS A CEILING; THE ANALYST MAY ONLY GO STRICTER. If the
 * install says numbers-only, an analyst cannot opt into publishing subjects —
 * a personal preference must not be able to widen what the organisation decided
 * to expose. The reverse is fine and encouraged.
 */
function scheduleFeedDetail(PDO $conn, int $analystId): string
{
    if (scheduleFeedMode($conn) === FEED_MODE_REF) return FEED_MODE_REF;

    try {
        $stmt = $conn->prepare(
            "SELECT preference_value FROM user_preferences
              WHERE analyst_id = ? AND preference_key = 'tickets_schedule_feed_detail' LIMIT 1"
        );
        $stmt->execute([$analystId]);
        if ($stmt->fetchColumn() === FEED_MODE_REF) return FEED_MODE_REF;
    } catch (Exception) {
        // Falls through to full, which is what they will have been seeing.
    }
    return FEED_MODE_FULL;
}

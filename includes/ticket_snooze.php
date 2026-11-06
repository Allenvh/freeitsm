<?php
/**
 * Snoozing a ticket — "I can't do anything with this until Thursday."
 *
 * WHY THIS EXISTS
 * ---------------
 * Half of a service desk's queue at any moment is waiting on somebody else: the
 * user who hasn't sent the screenshot, the supplier who ships on Tuesday, the
 * change that goes in at the weekend. Those tickets are not work — but they sit
 * in the inbox looking exactly like work, so an analyst re-reads them every
 * morning to re-decide they still can't be touched. Snoozing takes the ticket
 * out of the queue and brings it back at the moment it becomes actionable.
 *
 * WAKING IS THE CLOCK, NOT A CRON
 * -------------------------------
 * A snoozed ticket is defined as `snoozed_until > UTC_TIMESTAMP()` and nothing
 * else. Every list, count and badge tests that expression, so a ticket comes
 * back the instant its time passes whether or not anything is scheduled to run.
 * This is deliberate: an install with the crons switched off (or a box that was
 * asleep) must never leave a ticket buried past its wake time. The columns are
 * left holding a past value once it expires — harmless, and it means the audit
 * trail keeps the record of what was asked for.
 *
 * SNOOZE IS ON THE TICKET, NOT ON THE ANALYST
 * -------------------------------------------
 * Gmail-style snooze is personal, because an email belongs to one person. A
 * ticket doesn't: "waiting on the user until Thursday" is a fact about the
 * ticket, true for everyone who looks at it. So it sleeps for the whole desk,
 * and the banner names who put it to sleep and why, which is the information a
 * colleague needs when they wonder where it went.
 *
 * WHAT IT DOES NOT DO
 * -------------------
 * It does not touch SLA. The clock keeps running on a sleeping ticket, because
 * the customer's target is not suspended by the desk deciding to look away —
 * pausing it here would let a queue hit 100% by snoozing its way out of trouble.
 * It does not change status, and it does not hide the ticket from search.
 */

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/timezone.php';

/** Longest a ticket may sleep. A typo of 2206 instead of 2026 should be refused, not honoured. */
const SNOOZE_MAX_DAYS = 730;

/**
 * Does this database have the snooze columns yet?
 *
 * The inbox list and its folder counts are the busiest queries in the product,
 * and they now name `snoozed_until`. On an install that has pulled this update
 * but not yet run Database Verify, an unguarded reference would not degrade —
 * it would throw "Unknown column" and leave the analyst staring at an empty
 * ticket list, which is a far worse outcome than not having the feature. So
 * every fragment below is gated on this, cached for the request.
 */
function snoozeSchemaReady(PDO $conn): bool {
    static $ready = null;
    if ($ready !== null) return $ready;
    try {
        $ready = (bool)$conn->query("SHOW COLUMNS FROM `tickets` LIKE 'snoozed_until'")->fetch(PDO::FETCH_ASSOC);
    } catch (Exception) {
        $ready = false;
    }
    return $ready;
}

/**
 * Does this database have the scheduling columns yet? (#1161)
 *
 * Same reasoning as snoozeSchemaReady() immediately above, for
 * work_end_datetime / work_all_day: the inbox list carries the schedule so the
 * context menu can open Schedule on any row, and naming a column that does not
 * exist yet would empty the ticket list rather than merely disable a feature.
 *
 * Lives here rather than in a file of its own because this is already the home
 * of "the busiest query must not name a column that might be missing", and one
 * place to look for that rule beats two.
 */
function scheduleSchemaReady(PDO $conn): bool {
    static $ready = null;
    if ($ready !== null) return $ready;
    try {
        $ready = (bool)$conn->query("SHOW COLUMNS FROM `tickets` LIKE 'work_end_datetime'")->fetch(PDO::FETCH_ASSOC);
    } catch (Exception) {
        $ready = false;
    }
    return $ready;
}

/** Message shown when someone tries to snooze on a database that isn't ready. */
const SNOOZE_NOT_READY = 'Snooze needs a database update — run System → Database Verification.';

/**
 * The hour "Tomorrow" and "Next week" land on — Tickets → Settings → General,
 * default 09:00. Interpreted in the *snoozing analyst's* timezone, so a desk
 * spread across zones each gets their own start of day rather than the server's.
 */
function snoozeWakeHour(PDO $conn): int {
    static $cached = null;
    if ($cached !== null) return $cached;
    try {
        $stmt = $conn->prepare("SELECT setting_value FROM system_settings WHERE setting_key = 'snooze_wake_hour'");
        $stmt->execute();
        $val = $stmt->fetchColumn();
        $hour = ($val === false || $val === null || $val === '') ? 9 : (int)$val;
        $cached = ($hour >= 0 && $hour <= 23) ? $hour : 9;
    } catch (Exception) {
        $cached = 9;
    }
    return $cached;
}

/**
 * SQL fragment hiding sleeping tickets. Append to any WHERE (or LEFT JOIN ON)
 * that lists or counts the working queue. No placeholders, so it never disturbs
 * a caller's parameter ordering — the same property that lets the trashed-ticket
 * predicate be appended to $ttSql in get_ticket_counts.php.
 *
 * @param string $alias  the alias the calling query gives the tickets table
 */
function snoozeHiddenSql(PDO $conn, string $alias = 't'): string {
    // No columns yet = nothing is asleep, so hide nothing. The queue is exactly
    // what it was before the feature landed.
    if (!snoozeSchemaReady($conn)) return '';
    return " AND ($alias.snoozed_until IS NULL OR $alias.snoozed_until <= UTC_TIMESTAMP())";
}

/**
 * The complement: only sleeping tickets. Drives the Snoozed folder and its count.
 * Note the pre-upgrade answer is `1=0` and NOT the empty string — an empty
 * fragment here would turn "only the sleeping ones" into "every ticket you own",
 * which is the one failure mode worse than the folder simply being empty.
 *
 * @param string $alias  the alias the calling query gives the tickets table
 */
function snoozeOnlySql(PDO $conn, string $alias = 't'): string {
    if (!snoozeSchemaReady($conn)) return ' AND 1=0';
    return " AND $alias.snoozed_until IS NOT NULL AND $alias.snoozed_until > UTC_TIMESTAMP()";
}

/**
 * Turn a preset (or a custom local datetime) into the UTC instant to store.
 *
 * Presets are resolved server-side rather than in the browser so that "tomorrow
 * morning" means the same thing however the request arrived, and so the install's
 * wake hour is applied in one place. $localZone is the analyst's display zone
 * (Tz::current()); the arithmetic happens there and converts to UTC at the end,
 * which is what makes "tomorrow at 9" survive a DST boundary.
 *
 * @param string      $preset      three_hours | tonight | tomorrow | next_week | custom
 * @param string|null $customLocal 'Y-m-d H:i' in the analyst's zone, required when $preset is custom
 * @param string      $localZone   the analyst's display time zone name (Tz::current()), e.g. 'Europe/London'
 * @return string UTC datetime, 'Y-m-d H:i:s'
 * @throws Exception on an unknown preset, an unparseable date, or a time in the past
 */
function resolveSnoozeUntil(PDO $conn, string $preset, ?string $customLocal, string $localZone): string {
    $tz  = new DateTimeZone($localZone);
    $now = new DateTime('now', $tz);
    $hr  = snoozeWakeHour($conn);

    $target = clone $now;
    switch ($preset) {
        case 'three_hours':
            $target->modify('+3 hours');
            break;

        case 'tonight':
            // 18:00 today. The menu stops offering this after 18:00 precisely so
            // the label never lies, which leaves one way in: a page left open
            // across 18:00. Roll forward rather than refuse — the analyst wanted
            // an evening, and giving them the next one beats an error.
            $target->setTime(18, 0, 0);
            if ($target <= $now) $target->modify('+1 day');
            break;

        case 'tomorrow':
            $target->modify('+1 day')->setTime($hr, 0, 0);
            break;

        case 'next_week':
            // The next Monday at the wake hour. On a Monday this means *next*
            // Monday, not today — "next week" that lands in ten minutes is a bug.
            $target->modify('next monday')->setTime($hr, 0, 0);
            break;

        case 'custom':
            $raw = trim((string)$customLocal);
            if ($raw === '') throw new Exception('Pick a date and time');
            $parsed = DateTime::createFromFormat('Y-m-d H:i', $raw, $tz)
                   ?: DateTime::createFromFormat('Y-m-d H:i:s', $raw, $tz);
            if (!$parsed) throw new Exception('That date could not be read');
            $target = $parsed;
            $target->setTime((int)$target->format('H'), (int)$target->format('i'), 0);
            break;

        default:
            throw new Exception('Unknown snooze option');
    }

    // A snooze that has already elapsed would put the ticket straight back in the
    // queue and read as "nothing happened", so refuse it rather than pretend.
    if ($target <= $now) {
        throw new Exception('That time has already passed');
    }
    $limit = (clone $now)->modify('+' . SNOOZE_MAX_DAYS . ' days');
    if ($target > $limit) {
        throw new Exception('A ticket can be snoozed for at most ' . (SNOOZE_MAX_DAYS / 365) . ' years');
    }

    $target->setTimezone(new DateTimeZone('UTC'));
    return $target->format('Y-m-d H:i:s');
}

/**
 * Put one ticket to sleep. Caller has already checked access.
 * Deliberately does NOT bump updated_datetime — snoozing is the desk deciding
 * not to act, not activity on the ticket, and shoving it to the top of a sort
 * by last-updated on its way out of the list would be a lie.
 *
 * @param string $untilUtc  the wake time, UTC 'Y-m-d H:i:s' (from resolveSnoozeUntil)
 * @param string $reason  the analyst's note on why; '' stores NULL; cut to 255 characters
 */
function snoozeTicket(PDO $conn, int $ticketId, int $analystId, string $untilUtc, string $reason): void {
    if (!snoozeSchemaReady($conn)) {
        throw new Exception(SNOOZE_NOT_READY);
    }
    $conn->prepare(
        "UPDATE tickets
            SET snoozed_until = ?, snoozed_at = UTC_TIMESTAMP(), snoozed_by = ?, snooze_reason = ?
          WHERE id = ?"
    )->execute([$untilUtc, $analystId, ($reason === '' ? null : mb_substr($reason, 0, 255)), $ticketId]);

    snoozeAudit($conn, $ticketId, $analystId, null, $untilUtc . ($reason !== '' ? ' — ' . $reason : ''));
}

/**
 * Wake a ticket early. Returns true if it was actually asleep, so a caller can
 * stay quiet rather than announcing a wake that woke nothing.
 *
 * @param string $why  the audit trail text; '' records 'Woken'
 */
function wakeTicket(PDO $conn, int $ticketId, ?int $analystId, string $why = ''): bool {
    // Nothing can be asleep on a database with no snooze columns, so waking is a
    // truthful no-op rather than an error.
    if (!snoozeSchemaReady($conn)) return false;
    $stmt = $conn->prepare("SELECT snoozed_until FROM tickets WHERE id = ? AND snoozed_until > UTC_TIMESTAMP()");
    $stmt->execute([$ticketId]);
    $was = $stmt->fetchColumn();
    if ($was === false || $was === null) return false;

    $conn->prepare(
        "UPDATE tickets
            SET snoozed_until = NULL, snoozed_at = NULL, snoozed_by = NULL, snooze_reason = NULL
          WHERE id = ?"
    )->execute([$ticketId]);

    snoozeAudit($conn, $ticketId, $analystId ?? 0, (string)$was, $why !== '' ? $why : 'Woken');
    return true;
}

/**
 * The customer came back while the ticket was asleep — wake it.
 *
 * Sits alongside reopenTicketForCustomerReply() at all four inbound doors (email
 * import, portal reply, web chat, messaging ingest) for the same reason that one
 * is shared: "the customer replied" must mean the same thing whichever channel
 * they used. Snoozing is nearly always "waiting on them", so their reply is
 * exactly the event the snooze was waiting for — leaving it asleep would bury
 * the answer the desk asked for.
 *
 * Defensive to a fault: a customer's message must never fail because waking did.
 */
function wakeSnoozedTicketOnCustomerReply(PDO $conn, int $ticketId): bool {
    if ($ticketId <= 0) return false;
    try {
        return wakeTicket($conn, $ticketId, null, 'Woken by customer reply');
    } catch (Exception $e) {
        error_log('wakeSnoozedTicketOnCustomerReply failed for ticket ' . $ticketId . ': ' . $e->getMessage());
        return false;
    }
}

/**
 * Read the sleep state of one ticket for the reading pane: null when it is awake,
 * otherwise the wake time (UTC), who put it there and why.
 */
function snoozeStateFor(PDO $conn, int $ticketId): ?array {
    if (!snoozeSchemaReady($conn)) return null;
    try {
        $stmt = $conn->prepare(
            "SELECT t.snoozed_until, t.snoozed_at, t.snooze_reason, t.snoozed_by, a.full_name AS snoozed_by_name
               FROM tickets t
               LEFT JOIN analysts a ON a.id = t.snoozed_by
              WHERE t.id = ? AND t.snoozed_until > UTC_TIMESTAMP()"
        );
        $stmt->execute([$ticketId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;
        return [
            'snoozed_until'   => $row['snoozed_until'],
            'snoozed_at'      => $row['snoozed_at'],
            'reason'          => $row['snooze_reason'],
            'snoozed_by'      => $row['snoozed_by'] !== null ? (int)$row['snoozed_by'] : null,
            'snoozed_by_name' => $row['snoozed_by_name'],
        ];
    } catch (Exception) {
        // A pre-upgrade database has no snooze columns; no banner is the right
        // answer there, not a broken reading pane.
        return null;
    }
}

/**
 * Best-effort trail entry. Never fails the action it is recording.
 *
 * @param ?string $old  the previous wake time (UTC), or null when snoozing
 * @param ?string $new  the new wake time with its reason, or the reason it was woken
 */
function snoozeAudit(PDO $conn, int $ticketId, int $analystId, ?string $old, ?string $new): void {
    try {
        $conn->prepare(
            "INSERT INTO ticket_audit (ticket_id, analyst_id, field_name, old_value, new_value, created_datetime)
             VALUES (?, ?, 'Snooze', ?, ?, UTC_TIMESTAMP())"
        )->execute([$ticketId, $analystId, $old, $new]);
    } catch (Exception) { /* audit is best-effort */ }
}

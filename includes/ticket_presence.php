<?php
/**
 * Collision detection — who else is in this ticket right now.
 *
 * WHY THIS EXISTS
 * ---------------
 * Two analysts working the same ticket without knowing it is the oldest
 * service-desk annoyance there is: both write a reply, the customer gets two
 * answers that may contradict each other, and one analyst's ten minutes are
 * gone. It happens most on a busy morning, and most of all in Unassigned, where
 * several people triage the same list at once. Nothing in FreeITSM warned you.
 *
 * PRESENCE IS A HEARTBEAT, NOT A SESSION
 * --------------------------------------
 * There is no "join" and no "leave" that has to succeed. An open ticket sends a
 * heartbeat every few seconds; a row is "live" only while `last_seen` is within
 * PRESENCE_STALE_SECONDS. So a closed tab, a killed browser, a flat battery or a
 * dropped network all resolve themselves in one stale window with nothing having
 * to run — the same reasoning that made snooze's waking need no cron (#933).
 *
 * The explicit leave (sent on navigating away) is an OPTIMISATION: it makes the
 * indicator disappear at once rather than in half a minute. Nothing depends on
 * it arriving, which is why it can ride on `sendBeacon` and be forgotten about.
 *
 * WHAT IT IS NOT
 * --------------
 * It is not a lock. Two people may legitimately need to write on one ticket —
 * an escalation and a holding reply, say — so this warns and never blocks. A
 * lock would need an owner, a timeout, a way to steal it and a way to explain
 * why the button is greyed out, and would be wrong most of the time it fired.
 *
 * It is also not an audit trail. Rows are overwritten by the next heartbeat and
 * deleted without ceremony; "who looked at this ticket last Tuesday" is a
 * different feature with different privacy questions, and this table cannot
 * answer it.
 */

require_once __DIR__ . '/functions.php';

/**
 * How long a heartbeat counts for. Three missed beats at the client's 10-second
 * interval — long enough to ride out a slow request or a tab the browser has
 * throttled in the background, short enough that a colleague who has genuinely
 * gone still disappears while you are looking at the screen.
 */
const PRESENCE_STALE_SECONDS = 30;

/** Rows older than this are litter and get purged opportunistically. */
const PRESENCE_PURGE_MINUTES = 10;

/**
 * Does this database have the presence table yet?
 *
 * Unlike snooze's column gate (#933), a missing table here is genuinely
 * harmless — presence is an additive strip in the reading pane, so the honest
 * degradation is "nobody is ever shown". Cached per request.
 */
function presenceSchemaReady(PDO $conn): bool {
    static $ready = null;
    if ($ready !== null) return $ready;
    try {
        $ready = (bool)$conn->query("SHOW TABLES LIKE 'ticket_presence'")->fetchColumn();
    } catch (Exception) {
        $ready = false;
    }
    return $ready;
}

/**
 * Record that this analyst is on this ticket. Upserts onto the (ticket, analyst)
 * unique key, so a ticket left open all afternoon is one row, not a thousand.
 *
 * $composing is the state worth warning about — the reply, forward or note
 * composer being open — as opposed to merely having the ticket on screen.
 *
 * @param bool $composing  true while the reply, forward or note composer is open; false when only viewing
 */
function presenceHeartbeat(PDO $conn, int $ticketId, int $analystId, bool $composing): void {
    if (!presenceSchemaReady($conn) || $ticketId <= 0 || $analystId <= 0) return;
    try {
        $conn->prepare(
            "INSERT INTO ticket_presence (ticket_id, analyst_id, last_seen, is_composing)
             VALUES (?, ?, UTC_TIMESTAMP(), ?)
             ON DUPLICATE KEY UPDATE last_seen = UTC_TIMESTAMP(), is_composing = VALUES(is_composing)"
        )->execute([$ticketId, $analystId, $composing ? 1 : 0]);
    } catch (Exception $e) {
        // A heartbeat is never worth an error on screen.
        error_log('presenceHeartbeat failed for ticket ' . $ticketId . ': ' . $e->getMessage());
    }
}

/**
 * Everyone ELSE currently on this ticket, most recently seen first.
 *
 * Joins `analysts` for the display name and filters `is_active`, so somebody
 * deactivated mid-session stops being announced. Returns [] on any problem —
 * an empty strip is a fine answer, a broken reading pane is not.
 */
function presenceOthers(PDO $conn, int $ticketId, int $analystId): array {
    if (!presenceSchemaReady($conn) || $ticketId <= 0) return [];
    try {
        $stmt = $conn->prepare(
            "SELECT p.analyst_id, p.is_composing, a.full_name,
                    TIMESTAMPDIFF(SECOND, p.last_seen, UTC_TIMESTAMP()) AS seconds_ago
               FROM ticket_presence p
               JOIN analysts a ON a.id = p.analyst_id
              WHERE p.ticket_id = ?
                AND p.analyst_id <> ?
                AND a.is_active = 1
                AND p.last_seen > DATE_SUB(UTC_TIMESTAMP(), INTERVAL ? SECOND)
              ORDER BY p.is_composing DESC, p.last_seen DESC"
        );
        $stmt->execute([$ticketId, $analystId, PRESENCE_STALE_SECONDS]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function ($r) {
            return [
                'analyst_id'  => (int)$r['analyst_id'],
                'name'        => $r['full_name'],
                'initials'    => presenceInitials($r['full_name']),
                'composing'   => (int)$r['is_composing'] === 1,
                'seconds_ago' => (int)$r['seconds_ago'],
            ];
        }, $rows);
    } catch (Exception) {
        return [];
    }
}

/** Drop this analyst's row — the optimisation that makes leaving instant. */
function presenceLeave(PDO $conn, int $ticketId, int $analystId): void {
    if (!presenceSchemaReady($conn) || $analystId <= 0) return;
    try {
        if ($ticketId > 0) {
            $conn->prepare("DELETE FROM ticket_presence WHERE ticket_id = ? AND analyst_id = ?")
                 ->execute([$ticketId, $analystId]);
        } else {
            // No ticket named (the browser is going away entirely) — clear the lot.
            $conn->prepare("DELETE FROM ticket_presence WHERE analyst_id = ?")->execute([$analystId]);
        }
    } catch (Exception) { /* leaving is best-effort by design */ }
}

/**
 * Bin rows nobody will ever read again.
 *
 * Ridden on the back of the heartbeat rather than given a cron, because it is
 * one indexed DELETE and the feature already depends on someone heartbeating —
 * if nothing is beating, nothing is accumulating either. Stale rows are already
 * invisible (presenceOthers filters on time), so this is housekeeping and not
 * correctness, and the cutoff is deliberately far beyond the stale window.
 */
function presencePurge(PDO $conn): void {
    if (!presenceSchemaReady($conn)) return;
    try {
        $conn->prepare("DELETE FROM ticket_presence WHERE last_seen < DATE_SUB(UTC_TIMESTAMP(), INTERVAL ? MINUTE)")
             ->execute([PRESENCE_PURGE_MINUTES]);
    } catch (Exception) { /* housekeeping only */ }
}

/**
 * "Sarah Williams" -> "SW". Mirrors the header avatar in includes/waffle-menu.php.
 *
 * @param string $fullName  the analyst's display name; blank gives '?'
 */
function presenceInitials(string $fullName): string {
    $parts = preg_split('/\s+/', trim($fullName), -1, PREG_SPLIT_NO_EMPTY);
    if (!$parts) return '?';
    $initials = mb_strtoupper(mb_substr($parts[0], 0, 1));
    if (count($parts) > 1) {
        $initials .= mb_strtoupper(mb_substr(end($parts), 0, 1));
    }
    return $initials;
}

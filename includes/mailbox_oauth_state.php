<?php
/**
 * The callback half of a mailbox's OAuth sign-in: is this `state` one that
 * api/tickets/mailbox_oauth_start.php issued to the analyst in this session?
 * See that file for why this exists.
 *
 * Deliberately free of functions.php, like the two callbacks that use it (GH #129).
 */

/**
 * Checks and uses up a state. Returns the mailbox id it was issued for, or null
 * when the state is unknown, expired, already used, or for somebody else.
 * The caller must have started the session.
 *
 * @param string $state  the state the provider sent back, exactly as received
 */
function mailboxOAuthConsumeState(string $state): ?int
{
    $analystId = (int)($_SESSION['analyst_id'] ?? 0);
    $pending   = (array)($_SESSION['mailbox_oauth_states'] ?? []);
    $match     = null;
    foreach ($pending as $issued => $entry) {
        if (is_string($issued) && hash_equals($issued, $state)) { $match = $entry; break; }
    }
    // One use only, whatever the outcome - a state that failed once is spent.
    if ($match !== null) {
        unset($pending[$state]);
        $_SESSION['mailbox_oauth_states'] = $pending;
    }
    if ($match === null || $analystId <= 0
        || (int)($match['analyst_id'] ?? 0) !== $analystId
        || (int)($match['expires'] ?? 0) < time()) {
        return null;
    }
    return (int)$match['mailbox_id'];
}

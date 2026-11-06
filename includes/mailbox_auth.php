<?php
/**
 * What "authenticated" means for a mailbox — defined ONCE.
 *
 * ⚠️ WHY THIS EXISTS. Four separate queries each carried their own copy of
 *
 *     CASE WHEN token_data IS NOT NULL AND token_data != '' THEN 1 ELSE 0 END
 *
 * all written before Basic IMAP mailboxes existed. `token_data` holds the OAuth
 * token, so that test is structurally false for IMAP: those mailboxes authenticate
 * with a stored username + password and never hold a token at all. No credential
 * is ever written to that column for them — see save_mailbox.php, where the only
 * mention sets it to NULL.
 *
 * The result was a mailbox that collected mail perfectly well while the company
 * routing summary, the routing tester and the topology view all called it "not
 * authenticated", and the routing panel warned that no mail would flow. The
 * mailbox list disagreed with all three, because it had been taught about IMAP
 * separately, in PHP, months later.
 *
 * The copies were the bug. One expression, used everywhere, cannot drift again.
 *
 * Columns referenced (`provider`, `imap_password`) are not new: get_mailboxes.php
 * and get_topology.php already select them, so this adds no schema requirement
 * that the mailbox screens did not already have.
 */

/**
 * SQL returning 1 when a `target_mailboxes` row can log in, 0 when it cannot.
 *
 * OAuth providers (Microsoft, Google) prove it with a token; Basic IMAP proves it
 * with a stored password. Either one means the mailbox is authenticated.
 *
 * @param  string $prefix Optional table alias INCLUDING the dot, e.g. "m." — the
 *                        current callers all query `target_mailboxes` unaliased.
 * @return string A CASE expression safe to embed in a SELECT list or WHERE.
 */
function mailboxAuthenticatedSql(string $prefix = ''): string {
    return "CASE WHEN ({$prefix}token_data IS NOT NULL AND {$prefix}token_data <> '')"
         . " OR ({$prefix}provider = 'imap' AND {$prefix}imap_password IS NOT NULL AND {$prefix}imap_password <> '')"
         . " THEN 1 ELSE 0 END";
}

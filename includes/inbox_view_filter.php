<?php
/**
 * The inbox's view filter (GH #149): "My tickets" and "Hide closed".
 *
 * 🔑 ONE HOME, because two endpoints must agree to the ticket: the folder
 * counts (get_ticket_counts.php) and the list (get_emails.php). If they each
 * spelled out "mine" or "closed" for themselves, a department badge would say
 * 12 while the list showed 11 the first time one of them was edited — the
 * count-disagrees-with-list bug this inbox already carries scars from.
 *
 * 🔴 A FILTER, NOT A PERMISSION. Both fragments only ever narrow what the
 * analyst could already see; neither widens it.
 *
 * Both fragments are PLACEHOLDER-FREE on purpose. The counts endpoint appends
 * its shared predicate to WHERE clauses and LEFT JOIN ON clauses alike, and
 * relies on it binding no parameters so the positional order of everything
 * around it is untouched. The analyst id is an int cast, never user input.
 */

/**
 * Read the filter from the request. Absent means off, so every existing caller
 * of either endpoint keeps exactly the behaviour it had.
 */
function inboxViewFilterFromRequest(): array {
    return [
        'mine'        => !empty($_GET['mine']),
        'hide_closed' => !empty($_GET['hide_closed']),
    ];
}

/**
 * The SQL fragment for a filter, against the tickets alias given.
 *
 * "Mine" means ASSIGNED to me — t.assigned_analyst_id, the same column the
 * Analyst folders count. Not owner_id: the two are stored separately and
 * disagree on most rows, and "My tickets" must match the number on your own
 * analyst folder rather than quietly answering a different question.
 *
 * "Closed" means any status flagged is_closed, not a status called "Closed" —
 * an install can have Resolved, Cancelled and Closed, all of them closed.
 * NOT EXISTS rather than NOT IN so a ticket with no status is kept: NOT IN
 * against a NULL status_id is NULL, and would silently drop it.
 *
 * @param array $filter  array{mine:bool,hide_closed:bool} as from inboxViewFilterFromRequest()
 * @param string $alias  the alias the calling query gives the tickets table (trusted - written into the SQL)
 */
function inboxViewFilterSql(array $filter, int $analystId, string $alias = 't'): string {
    $sql = '';
    if (!empty($filter['mine'])) {
        $sql .= " AND $alias.assigned_analyst_id = " . (int)$analystId;
    }
    if (!empty($filter['hide_closed'])) {
        $sql .= " AND NOT EXISTS (SELECT 1 FROM ticket_statuses vf_ts"
              . " WHERE vf_ts.id = $alias.status_id AND vf_ts.is_closed = 1)";
    }
    return $sql;
}

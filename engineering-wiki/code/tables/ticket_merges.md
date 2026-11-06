# ticket_merges

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `source_ticket_id` | `INT NOT NULL` |  |
| `source_ticket_number` | `VARCHAR(50) NULL` |  |
| `target_ticket_id` | `INT NOT NULL` |  |
| `reference_mode` | `VARCHAR(20) NOT NULL DEFAULT 'survivor'` |  |
| `originals_mode` | `VARCHAR(20) NOT NULL DEFAULT 'thread'` |  |
| `moved_email_ids` | `TEXT NULL` | Which messages moved, as a JSON array of email ids — see the same column on ticket_splits for why text and not a child table. Recorded now so unmerge becomes possible later; merges done BEFORE this column existed cannot be reconstructed, which is the whole reason for adding it early. |
| `moved_related` | `TEXT NULL` | Everything ELSE the merge moved, as JSON {table: [row ids]} — notes, time entries, recordings, tasks, form submissions, CMDB/problem/change links. Messages alone are not a merge: unmerging without these would leave a ticket's notes and logged time stranded on somebody else's ticket. |
| `snapshot_email_id` | `INT NULL` | The system message carrying the HTML snapshot, when the originals mode made one. Created by the merge rather than moved, so it is not in moved_email_ids, and an unmerge has to delete it (and its file). |
| `source_prev_status_id` | `INT NULL` | What the source ticket looked like before it was closed by the merge. A merge sets it to the install's first closed status; putting it back to "Open" would be a guess, and a wrong one for anything that had been resolved before it was merged. |
| `source_prev_closed_datetime` | `DATETIME NULL` |  |
| `undone_datetime` | `DATETIME NULL` |  |
| `undone_by_id` | `INT NULL` |  |
| `merged_by_id` | `INT NULL` |  |
| `merged_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md):277 | `unmergeTicket` | update | `undone_datetime`, `undone_by_id` |  |
| [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md):594 | `mergeOneTicket` | insert | `source_ticket_id`, `source_ticket_number`, `target_ticket_id`, `reference_mode`, `originals_mode`, `moved_email_ids`, `moved_related`, `snapshot_email_id`, `source_prev_status_id`, `source_prev_closed_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/tickets/ai_merge_summary.php](/code/files/api/tickets/ai_merge_summary.php.md) | L133 |  |
| [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md) | L275 |  |
| [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md) | unmergeTicket L175, unmergeTicket L266, mergedAwayInfo L307 |  |


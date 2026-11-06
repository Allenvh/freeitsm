# ticket_splits

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `source_ticket_id` | `INT NOT NULL` |  |
| `source_ticket_number` | `VARCHAR(50) NULL` |  |
| `new_ticket_id` | `INT NOT NULL` |  |
| `new_ticket_number` | `VARCHAR(50) NULL` |  |
| `message_count` | `INT NOT NULL DEFAULT 0` |  |
| `moved_email_ids` | `TEXT NULL` | EXACTLY which messages moved, as a JSON array of email ids. A count alone cannot be undone: you would have to guess which rows to send back, and guessing wrong scatters a conversation across two tickets permanently.  Stored as text rather than a child table on purpose. This list is only ever read whole ("undo this split") — never joined, filtered or aggregated — and a child table with an FK to `emails` would CASCADE the record away the day one of those messages is deleted, which is precisely when you most want to know what happened. |
| `marker_email_id` | `INT NULL` | The system message left behind in the original thread, so an undo can remove it rather than leaving a marker pointing at a split that no longer exists. |
| `undone_datetime` | `DATETIME NULL` |  |
| `undone_by_id` | `INT NULL` |  |
| `split_by_id` | `INT NULL` |  |
| `split_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/ticket_split.php](/code/files/includes/ticket_split.php.md):217 | `splitTicket` | insert | `source_ticket_id`, `source_ticket_number`, `new_ticket_id`, `new_ticket_number`, `message_count`, `moved_email_ids`, `marker_email_id`, `split_by_id`, `split_datetime` |  |
| [includes/ticket_split.php](/code/files/includes/ticket_split.php.md):472 | `undoSplit` | update | `undone_datetime`, `undone_by_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/ticket_split.php](/code/files/includes/ticket_split.php.md) | splitMarkerEmailIds L90, splitInfoFor L364, splitInfoFor L370, undoSplit L404 |  |


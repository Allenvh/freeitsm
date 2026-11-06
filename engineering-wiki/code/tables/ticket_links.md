# ticket_links

Ticket-to-ticket links (self-referential, typed): related / duplicate / parent.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `source_ticket_id` | `INT NOT NULL` |  |
| `target_ticket_id` | `INT NOT NULL` |  |
| `relation_type` | `VARCHAR(20) NOT NULL DEFAULT 'related'` |  |
| `created_by_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/ticket_links.php](/code/files/includes/ticket_links.php.md):158 | `ticketLinkCreate` | insert | `source_ticket_id`, `target_ticket_id`, `relation_type`, `created_by_id`, `created_datetime` |  |
| [includes/ticket_links.php](/code/files/includes/ticket_links.php.md):199 | `ticketLinkRemove` | delete |  |  |
| [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md):257 | `unmergeTicket` | delete |  |  |
| [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md):553 | `mergeOneTicket` | update | `source_ticket_id` |  |
| [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md):554 | `mergeOneTicket` | update | `target_ticket_id` |  |
| [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md):555 | `mergeOneTicket` | delete |  |  |
| [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md):557 | `mergeOneTicket` | delete |  |  |
| [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md):613 | `mergeOneTicket` | insert | `source_ticket_id`, `target_ticket_id`, `relation_type`, `created_by_id`, `created_datetime` |  |
| [includes/ticket_split.php](/code/files/includes/ticket_split.php.md):234 | `splitTicket` | insert | `source_ticket_id`, `target_ticket_id`, `relation_type`, `created_by_id`, `created_datetime` |  |
| [includes/ticket_split.php](/code/files/includes/ticket_split.php.md):463 | `undoSplit` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/ticket_links.php](/code/files/includes/ticket_links.php.md) | ticketLinkCreate L115, ticketLinkCreate L124, ticketLinkCreate L131, ticketLinkCreate L141, ticketLinkCreate L151, ticketLinkRemove L186 ... |  |
| [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) | warbotToolRelatedTickets L930 |  |


# ticket_number_counters

Ticket numbering (GH #71) ⚠️ ticket_number_counters has NO auto-increment id: counter_key is the primary key, which is what lets the read-and-increment be one statement.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `counter_key` | `VARCHAR(64) NOT NULL` |  |
| `next_value` | `BIGINT NOT NULL DEFAULT 1` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/ticket_numbering.php](/code/files/includes/ticket_numbering.php.md):193 | `TicketNumbering::windCounterTo` | insert | `counter_key`, `next_value` |  |
| [includes/ticket_numbering.php](/code/files/includes/ticket_numbering.php.md):433 | `TicketNumbering::claimNext` | insert | `counter_key`, `next_value` |  |


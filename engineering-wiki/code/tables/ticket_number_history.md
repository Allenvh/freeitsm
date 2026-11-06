# ticket_number_history

⚠️ The PK is declared in db_verify.php's `$primaryKeys` map, NOT here — this array is columns only, and a 'PRIMARY KEY' entry would be built as a column literally called that.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `ticket_id` | `INT NOT NULL` |  |
| `ticket_number` | `VARCHAR(50) NOT NULL` |  |
| `reason` | `VARCHAR(30) NOT NULL DEFAULT 'renumber'` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/ticket_numbering.php](/code/files/includes/ticket_numbering.php.md):812 | `TicketNumbering::applyRenumber` | insert | `ticket_id`, `ticket_number`, `reason` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/ticket_numbering.php](/code/files/includes/ticket_numbering.php.md) | TicketNumbering::inUse L500, TicketNumbering::findTicketId L526, TicketNumbering::assertPlanSafe L782 |  |


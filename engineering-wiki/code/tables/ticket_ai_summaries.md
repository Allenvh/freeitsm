# ticket_ai_summaries

The AI-written ticket summary, kept as a history — see freeitsm.sql for why a refresh writes a new version instead of overwriting the old one.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `ticket_id` | `INT NOT NULL` |  |
| `kind` | `VARCHAR(16) NOT NULL DEFAULT 'summary'` | 'summary' = the standing panel; 'read' = a "read it for me" briefing. |
| `version` | `INT NOT NULL DEFAULT 1` | Numbered per (ticket, kind). |
| `summary` | `MEDIUMTEXT NOT NULL` |  |
| `provider` | `VARCHAR(32) NULL` |  |
| `model` | `VARCHAR(120) NULL` |  |
| `message_count` | `INT NOT NULL DEFAULT 0` |  |
| `note_count` | `INT NOT NULL DEFAULT 0` |  |
| `last_email_id` | `INT NULL` | The newest message this summary read: what makes "out of date" a fact. |
| `generated_by` | `INT NULL` | NULL = FreeITSM refreshed it by itself; an id = somebody pressed the button. |
| `truncated` | `TINYINT(1) NOT NULL DEFAULT 0` | Cut off before it finished — said out loud rather than served as if whole. |
| `tokens_in` | `INT NOT NULL DEFAULT 0` |  |
| `tokens_out` | `INT NOT NULL DEFAULT 0` |  |
| `created_at` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/tickets/ai_read.php](/code/files/api/tickets/ai_read.php.md):186 |  | insert | `ticket_id`, `kind`, `version`, `summary`, `provider`, `model`, `message_count`, `note_count`, `last_email_id`, `generated_by` |  |
| [api/tickets/ai_summary.php](/code/files/api/tickets/ai_summary.php.md):207 |  | insert | `ticket_id`, `version`, `summary`, `provider`, `model`, `message_count`, `note_count`, `last_email_id`, `generated_by`, `tokens_in` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/tickets/ai_read.php](/code/files/api/tickets/ai_read.php.md) | L103 |  |
| [api/tickets/ai_summary.php](/code/files/api/tickets/ai_summary.php.md) | L89 |  |
| [includes/ticket_ai.php](/code/files/includes/ticket_ai.php.md) | ticketAiLatestSummary L235 |  |


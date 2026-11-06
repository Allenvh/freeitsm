# webchat_messages

Pre-ticket chat transcript (AI 'deflect' mode) — see freeitsm.sql. sender is 'visitor'|'ai'|'agent'|'system'. Source for the ticket opening message + .txt log.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `conversation_id` | `INT NOT NULL` |  |
| `sender` | `VARCHAR(10) NOT NULL DEFAULT 'visitor'` |  |
| `body` | `LONGTEXT NULL` |  |
| `source_email_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md):442 | `webchatAddMessage` | insert | `conversation_id`, `sender`, `body`, `source_email_id`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md) | webchatGetMessages L461, webchatMirrorAgentReplies L504 |  |


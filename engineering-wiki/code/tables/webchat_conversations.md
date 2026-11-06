# webchat_conversations

One website chat conversation. token is the visitor's browser-held capability for this chat (needed on every send/poll); ticket_id is set lazily on the first message so a conversation maps to exactly one ticket. visitor_ip is for rate limits.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `channel_id` | `INT NOT NULL` |  |
| `token` | `VARCHAR(64) NOT NULL` |  |
| `ticket_id` | `INT NULL` |  |
| `visitor_name` | `VARCHAR(150) NULL` |  |
| `visitor_email` | `VARCHAR(255) NULL` |  |
| `visitor_ip` | `VARCHAR(45) NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `last_activity_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/webchat/start.php](/code/files/api/webchat/start.php.md):40 |  | insert | `channel_id`, `token`, `visitor_name`, `visitor_email`, `visitor_ip`, `created_datetime`, `last_activity_datetime` |  |
| [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md):249 | `webchatOpenTicket` | update | `ticket_id`, `last_activity_datetime` |  |
| [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md):369 | `webchatIngestMessage` | update | `last_activity_datetime` |  |
| [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md):447 | `webchatAddMessage` | update | `last_activity_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/webchat/public.php](/code/files/includes/webchat/public.php.md) | webchatLoadConversation L144, webchatRateLimitStart L166 |  |


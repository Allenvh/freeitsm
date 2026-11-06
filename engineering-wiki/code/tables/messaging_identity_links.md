# messaging_identity_links

Which requester a chat belongs to, on ONE channel (Telegram, PR #159). Keyed per channel, not per chat id - see freeitsm.sql for why.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `channel_id` | `INT NOT NULL` |  |
| `channel_type` | `VARCHAR(20) NOT NULL` |  |
| `external_id` | `VARCHAR(190) NOT NULL` |  |
| `user_id` | `INT NOT NULL` |  |
| `phone` | `VARCHAR(40) NULL` |  |
| `locale` | `VARCHAR(10) NULL` |  |
| `linked_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md):600 | `messagingTelegramResolveIdentity` | insert | `channel_id`, `channel_type`, `external_id`, `user_id`, `phone`, `locale` |  |
| [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md):642 | `messagingTelegramResolveIdentity` | update | `locale` |  |
| [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md):676 | `messagingTelegramLinkPhone` | update | `phone` |  |
| [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md):701 | `messagingTelegramLinkPhone` | update | `user_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/csat.php](/code/files/includes/csat.php.md) | csatChannelLocale L169 |  |
| [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) | messagingIdentityLinksReady L527, messagingTelegramResolveIdentity L588, messagingTelegramLinkPhone L724 |  |


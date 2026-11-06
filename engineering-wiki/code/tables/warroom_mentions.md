# warroom_mentions

Who was named in a message. One row PER RECIPIENT — `@everyone` is expanded at send time rather than stored as a flag, which keeps every query that asks "what has my name on it" a simple equality instead of a union. It also makes the record point-in-time correct: you notified the people who were entitled to that channel then, not whoever is entitled to it now.  ⚠️ There is NO read column. A mention counts as unread when its message is newer than your `warroom_reads` marker for that channel — reusing the state that already exists rather than adding a second one that can disagree with it.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `message_id` | `INT NOT NULL` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/warroom.php](/code/files/includes/warroom.php.md):615 | `warRoomEditMessage` | delete |  |  |
| [includes/warroom.php](/code/files/includes/warroom.php.md):649 | `warRoomDeleteMessage` | delete |  |  |
| [includes/warroom.php](/code/files/includes/warroom.php.md):842 | `warRoomStoreMentions` | insert | `message_id`, `analyst_id`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/warroom.php](/code/files/includes/warroom.php.md) | warRoomMyMentions L864, warRoomMentionCounts L920 |  |


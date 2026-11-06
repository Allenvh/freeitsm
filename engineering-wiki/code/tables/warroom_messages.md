# warroom_messages

analyst_id is NULLABLE on purpose (FK is ON DELETE SET NULL, see db_verify.php): deleting an analyst must not erase the conversation that happened during an incident. Those rows render as "Former analyst". channel_id is nullable ONLY so the column can be added to an installation created before channels existed; db_verify backfills it immediately.  edited/deleted are RECORDED, not hidden. This table is the record of what was said during an incident, so a message that changed after the fact says so, and a deleted one leaves a tombstone rather than a silent gap. The body and any attachments really are destroyed on delete — the point is that somebody can see a message was removed, not that its contents linger.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `channel_id` | `INT NULL` |  |
| `analyst_id` | `INT NULL` |  |
| `body` | `TEXT NOT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_bot` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `reply_to_id` | `INT NULL` |  |
| `edited_datetime` | `DATETIME NULL` |  |
| `deleted_datetime` | `DATETIME NULL` |  |
| `deleted_by` | `INT NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):1348 |  | update | `channel_id` |  |
| [includes/warbot/warbot.php](/code/files/includes/warbot/warbot.php.md):345 | `warbotPost` | insert | `channel_id`, `analyst_id`, `body`, `is_bot`, `reply_to_id`, `created_datetime` |  |
| [includes/warroom.php](/code/files/includes/warroom.php.md):608 | `warRoomEditMessage` | update | `body`, `edited_datetime` |  |
| [includes/warroom.php](/code/files/includes/warroom.php.md):652 | `warRoomDeleteMessage` | update | `body`, `deleted_datetime`, `deleted_by` |  |
| [includes/warroom.php](/code/files/includes/warroom.php.md):686 | `warRoomSend` | insert | `channel_id`, `analyst_id`, `body`, `created_datetime` |  |
| [includes/warroom.php](/code/files/includes/warroom.php.md):1269 | `warRoomPrune` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L1340, L1361 |  |
| [api/system/debug-tools/D008_war_room.php](/code/files/api/system/debug-tools/D008_war_room.php.md) | L257, L332 |  |
| [includes/warbot/warbot.php](/code/files/includes/warbot/warbot.php.md) | warbotAlreadyAnswered L368 |  |
| [includes/warroom.php](/code/files/includes/warroom.php.md) | warRoomChannelList L132, warRoomMessages L511, warRoomMessages L519, warRoomMessageRow L667, warRoomMyMentions L864, warRoomMentionCounts L920 ... |  |


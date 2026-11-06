# warroom_reads

How far each analyst has read in each channel, so the list can show what is new. One row per analyst per channel, upserted, and the id only ever moves forward — an out-of-order poll must not un-read a channel.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `channel_id` | `INT NOT NULL` |  |
| `last_read_message_id` | `INT NOT NULL DEFAULT 0` |  |
| `updated_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/warroom.php](/code/files/includes/warroom.php.md):1204 | `warRoomMarkRead` | insert | `analyst_id`, `channel_id`, `last_read_message_id`, `updated_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/warroom.php](/code/files/includes/warroom.php.md) | warRoomChannelList L132, warRoomMyMentions L864, warRoomMentionCounts L920 |  |


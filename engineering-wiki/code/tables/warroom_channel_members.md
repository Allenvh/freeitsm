# warroom_channel_members

Membership, for private custom channels and for DMs. Public custom channels also carry rows for whoever joined, so a member count means the same thing whichever kind it is. Team membership is NOT duplicated here — that lives in `analyst_teams` and is read from there, so the two can never disagree.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `channel_id` | `INT NOT NULL` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/warroom.php](/code/files/includes/warroom.php.md):346 | `warRoomAddMembers` | insert | `channel_id`, `analyst_id`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/warroom.php](/code/files/includes/warroom.php.md) | warRoomChannelList L132, warRoomChannelList L154, warRoomChannel L220, warRoomIsMember L274, warRoomAddMembers L346, warRoomChannelAudience L820 ... |  |


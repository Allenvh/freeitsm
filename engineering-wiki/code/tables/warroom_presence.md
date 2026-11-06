# warroom_presence

Who is currently in the war room. One row per analyst, upserted by the same poll that fetches messages — so presence costs no extra request and the table can never grow beyond the number of analysts. Shape deliberately mirrors `ticket_presence` (surrogate id + UNIQUE on the natural key), which is the pattern collision detection already established. channel_id records WHERE they are, which is how the sidebar separates "here" from "around".

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `channel_id` | `INT NULL` |  |
| `last_seen` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/warroom.php](/code/files/includes/warroom.php.md):1152 | `warRoomTouchPresence` | insert | `analyst_id`, `channel_id`, `last_seen` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/warroom.php](/code/files/includes/warroom.php.md) | warRoomDirectory L451, warRoomPresent L1174 |  |


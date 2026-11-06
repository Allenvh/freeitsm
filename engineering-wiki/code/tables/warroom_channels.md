# warroom_channels

War room — the fallback chat analysts use when Teams/Slack is unavailable.  Every conversation is a row here, of one of four KINDS, so that messages need exactly one foreign key instead of a nullable column per kind: all    — the one all-hands room. Always exists, always first. team   — one per team. team_id is UNIQUE and CASCADEs, so a team channel cannot be orphaned, duplicated, or renamed into a lie: its name is READ FROM `teams` at display time and never stored here. custom — somebody made it. This kind, and only this kind, has a lifecycle. dm     — a pair of analysts. `dm_key` is "<lower id>:<higher id>" and is UNIQUE, which is what stops two people opening a DM with each other simultaneously and getting one conversation each.  created_by is NULLABLE (FK SET NULL): a channel must outlive the person who opened it, or deleting a leaver would take the incident room with them.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `kind` | `VARCHAR(10) NOT NULL DEFAULT 'custom'` |  |
| `team_id` | `INT NULL` |  |
| `dm_key` | `VARCHAR(41) NULL` |  |
| `name` | `VARCHAR(120) NULL` |  |
| `topic` | `VARCHAR(255) NULL` |  |
| `is_private` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `created_by` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `archived_datetime` | `DATETIME NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):1298 |  | insert | `kind`, `created_datetime` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):1340 |  | insert | `kind`, `team_id`, `created_datetime` |  |
| [includes/warroom.php](/code/files/includes/warroom.php.md):88 | `warRoomEnsureChannels` | insert | `kind`, `name`, `created_datetime` |  |
| [includes/warroom.php](/code/files/includes/warroom.php.md):95 | `warRoomEnsureChannels` | insert | `kind`, `team_id`, `created_datetime` |  |
| [includes/warroom.php](/code/files/includes/warroom.php.md):317 | `warRoomCreateChannel` | insert | `kind`, `name`, `topic`, `is_private`, `created_by`, `created_datetime` |  |
| [includes/warroom.php](/code/files/includes/warroom.php.md):369 | `warRoomSetArchived` | update | `archived_datetime` |  |
| [includes/warroom.php](/code/files/includes/warroom.php.md):389 | `warRoomUpdateChannel` | update | `name`, `topic` |  |
| [includes/warroom.php](/code/files/includes/warroom.php.md):427 | `warRoomOpenDm` | insert | `kind`, `dm_key`, `is_private`, `created_by`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L1298, L1340, L1348 |  |
| [api/system/debug-tools/D008_war_room.php](/code/files/api/system/debug-tools/D008_war_room.php.md) | L177, L186, L187, L194, L201, L311 |  |
| [includes/warroom.php](/code/files/includes/warroom.php.md) | warRoomEnsureChannels L89, warRoomEnsureChannels L96, warRoomChannelList L132, warRoomChannel L220, warRoomOpenDm L420, warRoomMyMentions L864 ... |  |


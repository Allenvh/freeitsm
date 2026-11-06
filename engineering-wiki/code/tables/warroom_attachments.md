# warroom_attachments

⚠️ THERE IS NO content_type COLUMN, AND THAT IS THE POINT. The type an attachment is served as is derived from its extension against our own map at serve time (attachmentServeRules, security finding F5). Storing what the uploader claimed would create something for a future endpoint to trust by mistake, so the temptation is removed rather than merely documented. stored_name is the random name uploads.php generated; original_name is for display and for the download filename only, and never touches the path.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `message_id` | `INT NOT NULL` |  |
| `stored_name` | `VARCHAR(100) NOT NULL` |  |
| `original_name` | `VARCHAR(255) NOT NULL` |  |
| `size_bytes` | `INT NOT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/warroom.php](/code/files/includes/warroom.php.md):648 | `warRoomDeleteMessage` | delete |  |  |
| [includes/warroom.php](/code/files/includes/warroom.php.md):964 | `warRoomStoreAttachment` | insert | `message_id`, `stored_name`, `original_name`, `size_bytes`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/debug-tools/D008_war_room.php](/code/files/api/system/debug-tools/D008_war_room.php.md) | L229 |  |
| [includes/warroom.php](/code/files/includes/warroom.php.md) | warRoomMessages L565, warRoomDeleteMessage L642, warRoomAttachmentFor L996, warRoomPrune L1258, warRoomPrune L1279, warRoomSweepOrphanFiles L1318 |  |


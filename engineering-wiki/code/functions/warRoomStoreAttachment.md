# warRoomStoreAttachment()

Store one uploaded file against a message.

🔒 Storage goes through `uploadStoreFile()` — the ONE home for the rules — so
this inherits all three defences: an extension AND mime whitelist, a random
stored filename drawn from our own list rather than the caller's string, and
an .htaccess that disables execution in the directory. Nothing here reimplements
any of that, and nothing here calls `move_uploaded_file()`.

⚠️ NOTE WHAT IS *NOT* STORED: there is no content_type column. The type an
attachment is served as is derived from its extension against our own map at
serve time (security F5). Keeping the uploader's claim in the database would
only create something for a future endpoint to trust by mistake, so the
temptation is removed rather than documented.

```php
function warRoomStoreAttachment(PDO $conn, int $messageId, array $file): array
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 959.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$messageId` | `int` |  | the warroom_messages id it belongs to |
| `$file` | `array` |  | one entry from `$_FILES` - untrusted; `uploadStoreFile()` checks it |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 964 | `warRoomStoreAttachment` | **insert** [warroom_attachments](/code/tables/warroom_attachments.md) (`message_id`, `stored_name`, `original_name`, `size_bytes`, `created_datetime`) |  |

## Calls

[attachmentServeRules()](/code/functions/attachmentServeRules.md), [uploadStoreFile()](/code/functions/uploadStoreFile.md)

## Called from

- [api/war-room/send.php](/code/files/api/war-room/send.php.md)


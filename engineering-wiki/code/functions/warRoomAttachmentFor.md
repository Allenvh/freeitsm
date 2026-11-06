# warRoomAttachmentFor()

The row for an attachment the analyst is allowed to fetch, or null.

The channel check is done HERE rather than in the endpoint, so there is one
answer to "may I read this file" and it is the same answer as "may I read the
conversation it is in". Guessing an attachment id from another channel returns
nothing rather than a file.

```php
function warRoomAttachmentFor(PDO $conn, int $analystId, int $attachmentId): ?array
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 993.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$attachmentId` | `int` |  | the warroom_attachments id asked for |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 996 | `warRoomAttachmentFor` | read [warroom_attachments](/code/tables/warroom_attachments.md), read [warroom_messages](/code/tables/warroom_messages.md) |  |

## Calls

[warRoomCanAccessChannel()](/code/functions/warRoomCanAccessChannel.md)

## Called from

- [api/war-room/attachment.php](/code/files/api/war-room/attachment.php.md)


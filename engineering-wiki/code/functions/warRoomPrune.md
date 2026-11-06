# warRoomPrune()

Apply the retention setting.

Called from warRoomSend rather than from a cron job, deliberately: an emergency
tool must not depend on somebody having configured a scheduled task. The LIMIT
keeps any single send cheap — a long-overdue prune takes several sends to work
through instead of stalling one of them.

⚠️ ATTACHMENT FILES MUST GO WITH THE ROWS. The FK deletes the attachment record
when its message goes, but nothing deletes the bytes on disk, and a database
that says the file is gone while the file is still there is the worse half of
the two. So the paths are read BEFORE the delete and unlinked after it.

```php
function warRoomPrune(PDO $conn): void
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 1243.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1258 | `warRoomPrune` | read [warroom_attachments](/code/tables/warroom_attachments.md), read [warroom_messages](/code/tables/warroom_messages.md) |  |
| 1269 | `warRoomPrune` | **delete** [warroom_messages](/code/tables/warroom_messages.md) |  |
| 1279 | `warRoomPrune` | read [warroom_attachments](/code/tables/warroom_attachments.md) |  |

## Calls

[warRoomRetentionDays()](/code/functions/warRoomRetentionDays.md), [warRoomSweepOrphanFiles()](/code/functions/warRoomSweepOrphanFiles.md)

## Called from

- [includes/warroom.php](/code/files/includes/warroom.php.md) - in [warRoomSend()](/code/functions/warRoomSend.md)


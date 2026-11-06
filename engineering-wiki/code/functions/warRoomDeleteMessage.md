# warRoomDeleteMessage()

Delete a message: the author, or somebody with war_room.manage.

🔑 THE CONTENT GOES, THE ROW STAYS. The body is overwritten and the attachments
are destroyed on disk — so a mistakenly pasted password really is gone — but a
tombstone remains saying who removed it and when. A silent gap in an incident
transcript is worse than a visible one: the reader cannot tell whether something
was removed or never said, and that is exactly the question a post-incident
review asks.

```php
function warRoomDeleteMessage(
    PDO $conn,
    int $analystId,
    int $messageId,
    bool $mayManage = false
): bool
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 633.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$messageId` | `int` |  | the warroom_messages id |
| `$mayManage` | `bool` | `false` | true when the caller holds war_room.manage and may delete anyone's message |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 642 | `warRoomDeleteMessage` | read [warroom_attachments](/code/tables/warroom_attachments.md) |  |
| 648 | `warRoomDeleteMessage` | **delete** [warroom_attachments](/code/tables/warroom_attachments.md) |  |
| 649 | `warRoomDeleteMessage` | **delete** [warroom_mentions](/code/tables/warroom_mentions.md) |  |
| 652 | `warRoomDeleteMessage` | **update** [warroom_messages](/code/tables/warroom_messages.md) (`body`, `deleted_datetime`, `deleted_by`) |  |

## Calls

[warRoomCanAccessChannel()](/code/functions/warRoomCanAccessChannel.md), [warRoomMessageRow()](/code/functions/warRoomMessageRow.md)

## Called from

- [api/war-room/message.php](/code/files/api/war-room/message.php.md)


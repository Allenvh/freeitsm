# warRoomEditMessage()

Edit a message. The AUTHOR only — nobody else may put words in your mouth, not even an administrator, who can delete but not rewrite.

The edit is stamped rather than silent: this is the record of an incident, and a
message that changed after the fact has to say so. Mentions are re-resolved,
because an edit can add or remove a name.

```php
function warRoomEditMessage(PDO $conn, int $analystId, int $messageId, string $body): bool
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 596.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$messageId` | `int` |  | the warroom_messages id; only its author may edit it |
| `$body` | `string` |  | the new text; blank changes nothing; cut to WARROOM_MAX_BODY |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 608 | `warRoomEditMessage` | **update** [warroom_messages](/code/tables/warroom_messages.md) (`body`, `edited_datetime`) |  |
| 615 | `warRoomEditMessage` | **delete** [warroom_mentions](/code/tables/warroom_mentions.md) |  |

## Calls

[warRoomCanPostChannel()](/code/functions/warRoomCanPostChannel.md), [warRoomMessageRow()](/code/functions/warRoomMessageRow.md), [warRoomResolveMentions()](/code/functions/warRoomResolveMentions.md), [warRoomStoreMentions()](/code/functions/warRoomStoreMentions.md)

## Called from

- [api/war-room/message.php](/code/files/api/war-room/message.php.md)


# warRoomCanPostChannel()

May this analyst POST here? Read access, minus archived.

Archiving deliberately leaves a channel readable: the point of archiving an
incident channel is to stop it collecting new chatter, not to hide what was
said during the incident.

```php
function warRoomCanPostChannel(PDO $conn, int $analystId, int $channelId): bool
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 288.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$channelId` | `int` |  | the warroom_channels id |

**Returns:** `bool`

## Calls

[warRoomCanAccessChannel()](/code/functions/warRoomCanAccessChannel.md), [warRoomChannel()](/code/functions/warRoomChannel.md)

## Called from

- [api/war-room/send.php](/code/files/api/war-room/send.php.md)
- [includes/warroom.php](/code/files/includes/warroom.php.md) - in [warRoomEditMessage()](/code/functions/warRoomEditMessage.md)


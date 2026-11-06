# warRoomMayManage()

Who may reshape this channel: its creator, or someone with war_room.manage.

```php
function warRoomMayManage(PDO $conn, int $analystId, array $channel): bool
```

Defined in [api/war-room/channels.php](/code/files/api/war-room/channels.php.md), line 39.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$channel` | `array` |  | the channel row as loaded by `warRoomChannel()` - only created_by is read here |

**Returns:** `bool`

## Calls

[analystHasCapability()](/code/functions/analystHasCapability.md)

## Called from

- [api/war-room/channels.php](/code/files/api/war-room/channels.php.md)


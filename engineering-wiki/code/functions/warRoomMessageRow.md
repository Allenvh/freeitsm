# warRoomMessageRow()

One message row, raw. Callers do their own access checks.

```php
function warRoomMessageRow(PDO $conn, int $messageId): ?array
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 665.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$messageId` | `int` |  | the warroom_messages id |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 667 | `warRoomMessageRow` | read [warroom_messages](/code/tables/warroom_messages.md) |  |

## Called from

- [api/war-room/warbot.php](/code/files/api/war-room/warbot.php.md)
- [includes/warroom.php](/code/files/includes/warroom.php.md) - in [warRoomEditMessage()](/code/functions/warRoomEditMessage.md), [warRoomDeleteMessage()](/code/functions/warRoomDeleteMessage.md)


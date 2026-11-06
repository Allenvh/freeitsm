# warRoomUpdateChannel()

Rename / re-topic a custom channel. Team and DM names are derived, never stored.

```php
function warRoomUpdateChannel(PDO $conn, int $channelId, string $name, string $topic): bool
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 384.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$channelId` | `int` |  | the warroom_channels id; only a custom channel changes |
| `$name` | `string` |  | the new name; blank changes nothing; cut to 120 characters |
| `$topic` | `string` |  | the new topic; blank clears it; cut to 255 characters |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 389 | `warRoomUpdateChannel` | **update** [warroom_channels](/code/tables/warroom_channels.md) (`name`, `topic`) |  |

## Called from

- [api/war-room/channels.php](/code/files/api/war-room/channels.php.md)


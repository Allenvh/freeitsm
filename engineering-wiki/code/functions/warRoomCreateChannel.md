# warRoomCreateChannel()

Create a custom channel. Any analyst may — during an incident, needing an administrator to make you a room is exactly the wrong dependency.

```php
function warRoomCreateChannel(
    PDO $conn,
    int $analystId,
    string $name,
    string $topic = '',
    bool $isPrivate = false,
    array $memberIds = []
): int
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 306.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$name` | `string` |  | the channel name; blank throws 'name_required'; spaces collapsed, cut to 120 characters |
| `$topic` | `string` | `''` | an optional topic; blank stores NULL; cut to 255 characters |
| `$isPrivate` | `bool` | `false` | true for a members-only channel |
| `$memberIds` | `array` | `[]` | ignored unless `$isPrivate`; the creator is always added |

**Returns:** int the new channel id

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 317 | `warRoomCreateChannel` | **insert** [warroom_channels](/code/tables/warroom_channels.md) (`kind`, `name`, `topic`, `is_private`, `created_by`, `created_datetime`) |  |

## Calls

[warRoomAddMembers()](/code/functions/warRoomAddMembers.md)

## Called from

- [api/war-room/channels.php](/code/files/api/war-room/channels.php.md)


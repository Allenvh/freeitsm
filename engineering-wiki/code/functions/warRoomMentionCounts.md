# warRoomMentionCounts()

Unread mentions per channel, so the channel list can mark a mention differently from ordinary unread. Being named is a different event from having missed something, and one badge for both would hide the one that needs you.

```php
function warRoomMentionCounts(PDO $conn, int $analystId): array
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 917.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** array<int,int> channel_id => count

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 920 | `warRoomMentionCounts` | read [warroom_mentions](/code/tables/warroom_mentions.md), read [warroom_messages](/code/tables/warroom_messages.md), read [warroom_reads](/code/tables/warroom_reads.md) |  |

## Called from

- [api/war-room/poll.php](/code/files/api/war-room/poll.php.md)


# warRoomChannelList()

Every channel this analyst can see, in display order, with unread counts.

Order is deliberate and not alphabetical: all-hands, then teams, then custom
channels, then DMs. In an outage the room everyone shares should be the first
thing under the cursor, not the thing you scroll past your DMs to reach.

```php
function warRoomChannelList(PDO $conn, int $analystId, bool $includeArchived = false): array
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 123.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$includeArchived` | `bool` | `false` | true to include archived channels as well |

**Returns:** array<int,array{id:int,kind:string,name:string,topic:?string, is_private:bool,archived:bool,unread:int,members:?int}>

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 132 | `warRoomChannelList` | read [warroom_channel_members](/code/tables/warroom_channel_members.md), read [analysts](/code/tables/analysts.md), read [warroom_messages](/code/tables/warroom_messages.md), read [warroom_channels](/code/tables/warroom_channels.md), read [teams](/code/tables/teams.md), read [warroom_reads](/code/tables/warroom_reads.md) |  |
| 150 | `warRoomChannelList` | read [analyst_teams](/code/tables/analyst_teams.md) |  |
| 154 | `warRoomChannelList` | read [warroom_channel_members](/code/tables/warroom_channel_members.md) |  |

## Calls

[warRoomChannelName()](/code/functions/warRoomChannelName.md), [warRoomEnsureChannels()](/code/functions/warRoomEnsureChannels.md)

## Called from

- [api/war-room/channels.php](/code/files/api/war-room/channels.php.md)
- [api/war-room/poll.php](/code/files/api/war-room/poll.php.md)
- [war-room/index.php](/code/files/war-room/index.php.md)


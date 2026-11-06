# warRoomMyMentions()

The notifications panel: messages naming this analyst that they have not read.

"Unread" is derived from `warroom_reads`, not from a column here — so opening
the channel clears the mention, which is what a reader expects and what stops
the panel and the channel badge ever disagreeing.

⚠️ Channel access is re-checked in SQL, not assumed from the mention row. A
mention written before somebody was removed from a private channel must stop
being visible to them, not linger in their panel with a snippet attached.

```php
function warRoomMyMentions(PDO $conn, int $analystId, int $limit = 20): array
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 861.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$limit` | `int` | `20` | the most to return, 1-50 |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 864 | `warRoomMyMentions` | read [warroom_channel_members](/code/tables/warroom_channel_members.md), read [analysts](/code/tables/analysts.md), read [warroom_mentions](/code/tables/warroom_mentions.md), read [warroom_messages](/code/tables/warroom_messages.md), read [warroom_channels](/code/tables/warroom_channels.md), read [teams](/code/tables/teams.md), read [warroom_reads](/code/tables/warroom_reads.md) |  |
| 881 | `warRoomMyMentions` | read [analyst_teams](/code/tables/analyst_teams.md) |  |
| 885 | `warRoomMyMentions` | read [warroom_channel_members](/code/tables/warroom_channel_members.md) |  |

## Calls

[t()](/code/functions/t.md), [warRoomChannelName()](/code/functions/warRoomChannelName.md), [warRoomSnippet()](/code/functions/warRoomSnippet.md)

## Called from

- [api/war-room/alerts.php](/code/files/api/war-room/alerts.php.md)


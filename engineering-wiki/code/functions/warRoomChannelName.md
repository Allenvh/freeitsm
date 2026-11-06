# warRoomChannelName()

The label for a channel row.

A team channel shows the team's CURRENT name and a DM the other person's, both
read live rather than copied at creation — so renaming a team or an analyst
renames the channel, and there is no stored name that can quietly become wrong.

```php
function warRoomChannelName(array $row): string
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 194.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$row` | `array` |  | a channel row with kind, name, team_name and other_members (the other DM members' names) |

**Returns:** `string`

## Calls

[t()](/code/functions/t.md)

## Called from

- [includes/warbot/warbot.php](/code/files/includes/warbot/warbot.php.md) - in [warbotAnswer()](/code/functions/warbotAnswer.md)
- [includes/warroom.php](/code/files/includes/warroom.php.md) - in [warRoomChannelList()](/code/functions/warRoomChannelList.md), [warRoomMyMentions()](/code/functions/warRoomMyMentions.md), [warRoomSearch()](/code/functions/warRoomSearch.md), [warRoomTranscriptSince()](/code/functions/warRoomTranscriptSince.md)


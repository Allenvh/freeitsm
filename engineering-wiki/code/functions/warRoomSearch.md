# warRoomSearch()

Search the conversations this analyst can see.

🔑 DELIBERATELY `LIKE`, NOT THE FULL-TEXT CORPUS, for two reasons that both
bite exactly the strings people type into a war room.

  1. MySQL's full-text index has a MINIMUM TOKEN SIZE — three characters by
     default — and anything shorter is not ranked low, it is never recorded at
     all. `P1`, `AD`, `DB`, `VM`, `DC`, `HQ` and any single octet of an IP
     address are all below it. The search returns nothing, with no error.
  2. Full text tokenises on non-word characters, so `10.0.0.5`, `SRV-01` and a
     UNC path are split into pieces and cannot be matched as one string
     without boolean-mode quoting the user has no reason to know about.

A substring scan has neither problem. Chat volumes are small — retention bounds
the table, and it holds one row per message rather than per document — so the
scan is the right trade here even though it would be the wrong one for tickets.
⚠️ If war room volumes ever stop being small, the fix is an index, NOT moving to
full text: that would silently lose case 1 again.

Scoping is inside the query, not applied to the results afterwards: filtering
afterwards silently starves the result set when the matches are in channels you
cannot see.

```php
function warRoomSearch(
    PDO $conn,
    int $analystId,
    string $query,
    ?int $channelId = null,
    int $limit = 60
): array
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 1042.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$query` | `string` |  | the words typed; up to six, all must appear (substring match) |
| `$channelId` | `?int` | `null` | limit to one channel; null searches every channel the analyst can read |
| `$limit` | `int` | `60` | the most results, 1-200 |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1066 | `warRoomSearch` | read [warroom_channel_members](/code/tables/warroom_channel_members.md), read [analysts](/code/tables/analysts.md), read [warroom_messages](/code/tables/warroom_messages.md), read [warroom_channels](/code/tables/warroom_channels.md), read [teams](/code/tables/teams.md) |  |
| 1080 | `warRoomSearch` | read [analyst_teams](/code/tables/analyst_teams.md) |  |
| 1084 | `warRoomSearch` | read [warroom_channel_members](/code/tables/warroom_channel_members.md) |  |

## Calls

[t()](/code/functions/t.md), [warRoomChannelName()](/code/functions/warRoomChannelName.md), [warRoomSnippet()](/code/functions/warRoomSnippet.md)

## Called from

- [api/war-room/search.php](/code/files/api/war-room/search.php.md)
- [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) - in [warbotToolSearchChat()](/code/functions/warbotToolSearchChat.md)


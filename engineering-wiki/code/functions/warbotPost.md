# warbotPost()

Post Warbot's reply into a channel.

Written by the bot rather than by an analyst: analyst_id is NULL and is_bot is
1. ⚠️ Those two together are what distinguishes Warbot from a DELETED ANALYST,
which is the other reason analyst_id can be NULL — without the flag every one
of Warbot's messages would render as "Former analyst".

```php
function warbotPost(PDO $conn, int $channelId, string $body, int $replyTo = 0): int
```

Defined in [includes/warbot/warbot.php](/code/files/includes/warbot/warbot.php.md), line 338.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$channelId` | `int` |  | the warroom_channels id to post into |
| `$body` | `string` |  | the reply text; blank posts nothing, longer than WARROOM_MAX_BODY is cut |
| `$replyTo` | `int` | `0` | the warroom_messages id being answered; 0 for none |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 345 | `warbotPost` | **insert** [warroom_messages](/code/tables/warroom_messages.md) (`channel_id`, `analyst_id`, `body`, `is_bot`, `reply_to_id`, `created_datetime`) |  |

## Called from

- [api/war-room/warbot.php](/code/files/api/war-room/warbot.php.md)


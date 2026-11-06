# warbotAlreadyAnswered()

Has Warbot already answered this message?

The reply is triggered by the asker's browser after the send, so a double
submit, a retry, or two people with the page open could otherwise produce two
identical answers in the room.

```php
function warbotAlreadyAnswered(PDO $conn, int $messageId): bool
```

Defined in [includes/warbot/warbot.php](/code/files/includes/warbot/warbot.php.md), line 366.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$messageId` | `int` |  | the warroom_messages id of the question |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 368 | `warbotAlreadyAnswered` | read [warroom_messages](/code/tables/warroom_messages.md) |  |

## Called from

- [api/war-room/warbot.php](/code/files/api/war-room/warbot.php.md)


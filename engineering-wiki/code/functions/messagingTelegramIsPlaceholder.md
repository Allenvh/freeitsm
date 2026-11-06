# messagingTelegramIsPlaceholder()

Is this user id this chat's own placeholder (and not a real person)?

```php
function messagingTelegramIsPlaceholder(PDO $conn, int $userId, string $chatId): bool
```

Defined in [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md), line 551.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$userId` | `int` |  | the users.id to check |
| `$chatId` | `string` |  | the Telegram chat id |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 553 | `messagingTelegramIsPlaceholder` | read [users](/code/tables/users.md) |  |

## Calls

[messagingTelegramPlaceholderEmail()](/code/functions/messagingTelegramPlaceholderEmail.md)

## Called from

- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [messagingTelegramLinkPhone()](/code/functions/messagingTelegramLinkPhone.md)


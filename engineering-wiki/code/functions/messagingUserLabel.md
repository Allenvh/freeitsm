# messagingUserLabel()

"Alice Real <alice@example.com>" - for audit entries.

```php
function messagingUserLabel(PDO $conn, int $userId): string
```

Defined in [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md), line 791.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$userId` | `int` |  | the users.id to describe |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 793 | `messagingUserLabel` | read [users](/code/tables/users.md) |  |

## Called from

- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [messagingTelegramLinkPhone()](/code/functions/messagingTelegramLinkPhone.md)


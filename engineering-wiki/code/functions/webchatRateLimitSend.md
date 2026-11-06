# webchatRateLimitSend()

Cap inbound messages per conversation per minute. Exits (429) on breach.

```php
function webchatRateLimitSend(PDO $conn, int $ticketId): void
```

Defined in [includes/webchat/public.php](/code/files/includes/webchat/public.php.md), line 177.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 184 | `webchatRateLimitSend` | read [emails](/code/tables/emails.md) |  |

## Calls

[webchatFail()](/code/functions/webchatFail.md)

## Called from

- [api/webchat/send.php](/code/files/api/webchat/send.php.md)


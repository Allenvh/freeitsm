# webchatRateLimitStart()

Cap new conversations per IP per minute. Exits (429) on breach.

```php
function webchatRateLimitStart(PDO $conn, string $ip): void
```

Defined in [includes/webchat/public.php](/code/files/includes/webchat/public.php.md), line 159.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ip` | `string` |  | the visitor's IP address; '' skips the check |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 166 | `webchatRateLimitStart` | read [webchat_conversations](/code/tables/webchat_conversations.md) |  |

## Calls

[webchatFail()](/code/functions/webchatFail.md)

## Called from

- [api/webchat/start.php](/code/files/api/webchat/start.php.md)


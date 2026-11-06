# messagingIdentityLinksReady()

Has Database Verification created messaging_identity_links yet? Cached per request.

```php
function messagingIdentityLinksReady(PDO $conn): bool
```

Defined in [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md), line 522.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 527 | `messagingIdentityLinksReady` | read [messaging_identity_links](/code/tables/messaging_identity_links.md) |  |

## Called from

- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [messagingTelegramResolveIdentity()](/code/functions/messagingTelegramResolveIdentity.md)


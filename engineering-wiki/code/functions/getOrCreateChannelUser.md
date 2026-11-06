# getOrCreateChannelUser()

Get-or-create a placeholder requester keyed by phone, so repeat senders map to one user. The users table requires an email, so we synthesise a stable non-routable address ('+44…@whatsapp.local'); the real identity is the display name (the WhatsApp profile name) shown on the ticket.

```php
function getOrCreateChannelUser(
    PDO $conn,
    string $from,
    string $displayName,
    string $channelType
): ?int
```

Defined in [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md), line 950.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$from` | `string` |  | the normalised sender id; becomes the local part of the placeholder email |
| `$displayName` | `string` |  | the name for a new user; '' uses `$from`. An existing user's name is not changed |
| `$channelType` | `string` |  | the channel type; becomes the placeholder email's domain ('<type>.local') |

**Returns:** `?int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 958 | `getOrCreateChannelUser` | read [users](/code/tables/users.md) |  |
| 964 | `getOrCreateChannelUser` | **insert** [users](/code/tables/users.md) (`email`, `display_name`, `created_at`) |  |

## Called from

- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [ingestInboundMessage()](/code/functions/ingestInboundMessage.md), [resolveSlackRequester()](/code/functions/resolveSlackRequester.md), [resolveDirectoryRequester()](/code/functions/resolveDirectoryRequester.md), [messagingTelegramResolveIdentity()](/code/functions/messagingTelegramResolveIdentity.md)


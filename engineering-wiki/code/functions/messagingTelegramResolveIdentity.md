# messagingTelegramResolveIdentity()

Resolve (never blocks) which `users` row this chat belongs to on this channel.

Returns ['user_id'=>int, 'display_name'=>string (only when it should override
the caller's default), 'contact_only'=>bool (this message was a phone-number
share the caller should not turn into ticket text), 'is_new_placeholder'=>bool].

```php
function messagingTelegramResolveIdentity(
    PDO $conn,
    array $channel,
    string $chatId,
    array $msg,
    string $profileName
): array
```

Defined in [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md), line 571.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$channel` | `array` |  | the Telegram messaging_channels row, decrypted |
| `$chatId` | `string` |  | the Telegram chat id (the normalised sender) |
| `$msg` | `array` |  | the normalised message (language_code, contact, is_test) |
| `$profileName` | `string` |  | the sender's Telegram name; '' uses the chat id |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 588 | `messagingTelegramResolveIdentity` | read [messaging_identity_links](/code/tables/messaging_identity_links.md) |  |
| 600 | `messagingTelegramResolveIdentity` | **insert** [messaging_identity_links](/code/tables/messaging_identity_links.md) (`channel_id`, `channel_type`, `external_id`, `user_id`, `phone`, `locale`) |  |
| 642 | `messagingTelegramResolveIdentity` | **update** [messaging_identity_links](/code/tables/messaging_identity_links.md) (`locale`) |  |

## Calls

[getOrCreateChannelUser()](/code/functions/getOrCreateChannelUser.md), [messagingIdentityLinksReady()](/code/functions/messagingIdentityLinksReady.md), [messagingNormaliseLocale()](/code/functions/messagingNormaliseLocale.md), [messagingProvider()](/code/functions/messagingProvider.md), [messagingTelegramLinkPhone()](/code/functions/messagingTelegramLinkPhone.md)

## Called from

- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [ingestInboundMessage()](/code/functions/ingestInboundMessage.md)


# messagingTelegramLinkPhone()

A phone number has just arrived for this chat. Decide what it means, safely.

```php
function messagingTelegramLinkPhone(
    PDO $conn,
    array $channel,
    string $chatId,
    int $linkedUserId,
    string $phone,
    string $locale = 'en',
    bool $isTest = false
): array
```

Defined in [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md), line 669.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$channel` | `array` |  | the Telegram messaging_channels row, decrypted |
| `$chatId` | `string` |  | the Telegram chat id |
| `$linkedUserId` | `int` |  | the users.id the chat is linked to now - its placeholder, or a real person |
| `$phone` | `string` |  | the number Telegram vouched for, '+digits' |
| `$locale` | `string` | `'en'` | the language for the bot's reply |
| `$isTest` | `bool` | `false` | true for the settings self-test - nothing is sent to Telegram |

**Returns:** array{0:int,1:string} [the user the chat now resolves to, display name override]

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 676 | `messagingTelegramLinkPhone` | **update** [messaging_identity_links](/code/tables/messaging_identity_links.md) (`phone`) |  |
| 701 | `messagingTelegramLinkPhone` | **update** [messaging_identity_links](/code/tables/messaging_identity_links.md) (`user_id`) |  |
| 709 | `messagingTelegramLinkPhone` | read [tickets](/code/tables/tickets.md), read [emails](/code/tables/emails.md) |  |
| 717 | `messagingTelegramLinkPhone` | **update** [tickets](/code/tables/tickets.md) (`user_id`) |  |
| 724 | `messagingTelegramLinkPhone` | read [tickets](/code/tables/tickets.md), read [messaging_identity_links](/code/tables/messaging_identity_links.md) |  |
| 727 | `messagingTelegramLinkPhone` | **delete** [users](/code/tables/users.md) |  |
| 745 | `messagingTelegramLinkPhone` | read [users](/code/tables/users.md) |  |
| 753 | `messagingTelegramLinkPhone` | **update** [users](/code/tables/users.md) (`mobile`) |  |

## Calls

[findOpenChannelTicket()](/code/functions/findOpenChannelTicket.md), [messagingFindUsersByPhone()](/code/functions/messagingFindUsersByPhone.md), [messagingTelegramAck()](/code/functions/messagingTelegramAck.md), [messagingTelegramAudit()](/code/functions/messagingTelegramAudit.md), [messagingTelegramIsPlaceholder()](/code/functions/messagingTelegramIsPlaceholder.md), [messagingTelegramPlaceholderEmail()](/code/functions/messagingTelegramPlaceholderEmail.md), [messagingUserLabel()](/code/functions/messagingUserLabel.md), [resolveTicketTenantForChannel()](/code/functions/resolveTicketTenantForChannel.md)

## Called from

- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [messagingTelegramResolveIdentity()](/code/functions/messagingTelegramResolveIdentity.md)


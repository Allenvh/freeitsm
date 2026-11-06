# loadMessagingChannel()

Load a messaging_channels row with its credentials decrypted + JSON-decoded. Returns null if not found. Safe to call before db_verify has run the table (returns null rather than throwing).

```php
function loadMessagingChannel(PDO $conn, $channelId): ?array
```

Defined in [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), line 69.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$channelId` |  |  | the messaging_channels id (cast to int) |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 72 | `loadMessagingChannel` | read [messaging_channels](/code/tables/messaging_channels.md) |  |

## Calls

[decryptValue()](/code/functions/decryptValue.md), [messagingDecodeCredentials()](/code/functions/messagingDecodeCredentials.md)

## Called from

- [api/messaging/save_channel.php](/code/files/api/messaging/save_channel.php.md)
- [api/messaging/send_attachment.php](/code/files/api/messaging/send_attachment.php.md)
- [api/messaging/send_message.php](/code/files/api/messaging/send_message.php.md)
- [api/messaging/send_template.php](/code/files/api/messaging/send_template.php.md)
- [api/messaging/slack_diagnose.php](/code/files/api/messaging/slack_diagnose.php.md)
- [api/messaging/slack_manifest.php](/code/files/api/messaging/slack_manifest.php.md)
- [api/messaging/teams_package.php](/code/files/api/messaging/teams_package.php.md)
- [api/messaging/telegram_connect.php](/code/files/api/messaging/telegram_connect.php.md)
- [api/messaging/test_channel.php](/code/files/api/messaging/test_channel.php.md)
- [api/messaging/webhook.php](/code/files/api/messaging/webhook.php.md)
- [api/webchat/escalate.php](/code/files/api/webchat/escalate.php.md)
- [api/webchat/send.php](/code/files/api/webchat/send.php.md)
- [includes/csat.php](/code/files/includes/csat.php.md) - in [csatTicketChannel()](/code/functions/csatTicketChannel.md)


# messagingWebhookUrl()

The full public webhook URL for a channel — what gets pasted into the provider. The app root is derived from SCRIPT_NAME so it works under any sub-path.

```php
function messagingWebhookUrl(PDO $conn, int $channelId): string
```

Defined in [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), line 144.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$channelId` | `int` |  | the messaging_channels id, put in the URL's ?channel= parameter |

**Returns:** `string`

## Calls

[messagingPublicBaseUrl()](/code/functions/messagingPublicBaseUrl.md)

## Called from

- [api/messaging/get_channels.php](/code/files/api/messaging/get_channels.php.md)
- [api/messaging/slack_diagnose.php](/code/files/api/messaging/slack_diagnose.php.md)
- [api/messaging/slack_manifest.php](/code/files/api/messaging/slack_manifest.php.md)
- [api/messaging/telegram_connect.php](/code/files/api/messaging/telegram_connect.php.md)
- [api/messaging/test_channel.php](/code/files/api/messaging/test_channel.php.md) - in [testReachability()](/code/functions/testReachability.md)
- [includes/messaging/MattermostProvider.php](/code/files/includes/messaging/MattermostProvider.php.md) - in `MattermostProvider::buttonCallbackUrl`


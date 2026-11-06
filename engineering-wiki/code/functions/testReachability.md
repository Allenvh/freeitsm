# testReachability()

Confirm the channel's own public webhook URL is reachable from the internet.

```php
function testReachability(PDO $conn, int $channelId): array
```

Defined in [api/messaging/test_channel.php](/code/files/api/messaging/test_channel.php.md), line 112.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$channelId` | `int` |  | the messaging_channels id whose webhook URL is probed |

**Returns:** `array`

## Calls

[messagingWebhookUrl()](/code/functions/messagingWebhookUrl.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [api/messaging/test_channel.php](/code/files/api/messaging/test_channel.php.md)


# messagingProvider()

Build the right provider for a channel row (credentials already decrypted into an array by loadMessagingChannel). Throws on an unknown provider.

```php
function messagingProvider(array $channel): MessagingProvider
```

Defined in [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), line 40.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$channel` | `array` |  | a messaging_channels row with credentials already decoded; 'provider' picks the class (default twilio) |

**Returns:** `MessagingProvider`

## Called from

- [api/messaging/send_attachment.php](/code/files/api/messaging/send_attachment.php.md)
- [api/messaging/send_message.php](/code/files/api/messaging/send_message.php.md)
- [api/messaging/send_template.php](/code/files/api/messaging/send_template.php.md)
- [api/messaging/slack_diagnose.php](/code/files/api/messaging/slack_diagnose.php.md)
- [api/messaging/telegram_connect.php](/code/files/api/messaging/telegram_connect.php.md)
- [api/messaging/test_channel.php](/code/files/api/messaging/test_channel.php.md) - in [testCredentials()](/code/functions/testCredentials.md)
- [api/messaging/webhook.php](/code/files/api/messaging/webhook.php.md)
- [includes/csat.php](/code/files/includes/csat.php.md) - in [csatSendInChannel()](/code/functions/csatSendInChannel.md)
- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [ingestInboundMessage()](/code/functions/ingestInboundMessage.md), [resolveSlackRequester()](/code/functions/resolveSlackRequester.md), [resolveDirectoryRequester()](/code/functions/resolveDirectoryRequester.md), [messagingTelegramResolveIdentity()](/code/functions/messagingTelegramResolveIdentity.md), [messagingRecordCsatReply()](/code/functions/messagingRecordCsatReply.md), [messagingTelegramAck()](/code/functions/messagingTelegramAck.md)


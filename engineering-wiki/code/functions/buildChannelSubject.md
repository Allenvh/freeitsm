# buildChannelSubject()

A short, human ticket subject from the first line of the first message.

```php
function buildChannelSubject(string $channelType, string $displayName, string $body): string
```

Defined in [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md), line 998.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$channelType` | `string` |  | the channel type, used as the subject's label |
| `$displayName` | `string` |  | the sender's name, used when the message has no text |
| `$body` | `string` |  | the first message's text; its first line, cut to 80 characters, becomes the subject |

**Returns:** `string`

## Called from

- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [ingestInboundMessage()](/code/functions/ingestInboundMessage.md)
- [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md) - in [webchatInsertInbound()](/code/functions/webchatInsertInbound.md), [webchatIngestMessage()](/code/functions/webchatIngestMessage.md), [webchatPromoteToTicket()](/code/functions/webchatPromoteToTicket.md)


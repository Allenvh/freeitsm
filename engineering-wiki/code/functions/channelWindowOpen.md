# channelWindowOpen()

True when the last inbound message (UTC) was under 24 hours ago, so a free-form reply is still allowed.

```php
function channelWindowOpen(?string $lastInboundAt): bool
```

Defined in [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), line 468.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$lastInboundAt` | `?string` |  | the last inbound message time as a 'Y-m-d H:i:s' UTC string; null or empty means closed |

**Returns:** `bool`

## Called from

- [api/messaging/send_attachment.php](/code/files/api/messaging/send_attachment.php.md)
- [api/messaging/send_message.php](/code/files/api/messaging/send_message.php.md)
- [api/tickets/get_ticket_thread.php](/code/files/api/tickets/get_ticket_thread.php.md)
- [includes/csat.php](/code/files/includes/csat.php.md) - in [csatTicketChannel()](/code/functions/csatTicketChannel.md)


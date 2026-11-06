# channelHasServiceWindow()

True for channels with a reply window (WhatsApp); web chat, Slack, Telegram, Teams and Mattermost have none.

```php
function channelHasServiceWindow(string $channelType): bool
```

Defined in [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), line 458.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$channelType` | `string` |  | the channel type, e.g. 'whatsapp' or 'slack' |

**Returns:** `bool`

## Called from

- [api/messaging/send_attachment.php](/code/files/api/messaging/send_attachment.php.md)
- [api/messaging/send_message.php](/code/files/api/messaging/send_message.php.md)
- [api/tickets/get_ticket_thread.php](/code/files/api/tickets/get_ticket_thread.php.md)
- [includes/csat.php](/code/files/includes/csat.php.md) - in [csatTicketChannel()](/code/functions/csatTicketChannel.md)


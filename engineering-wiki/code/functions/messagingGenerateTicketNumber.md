# messagingGenerateTicketNumber()

The number for a ticket opened by a chat (WhatsApp, Telegram, Slack, Teams, Mattermost, web chat).

TRAP: never make a ticket number here, or anywhere else, by hand. This used
  to roll its own XXX-NNN-NNNNN, so on an install set to TICKET-{######}
  every chat ticket still came out as JNL-471-47705 (GH #71 moved three
  generators onto TicketNumbering and missed this one, plus catalogue
  approvals, merge and split). Pass the ticket's company: per-company
  numbering and {COMPANY} need it.

```php
function messagingGenerateTicketNumber(PDO $conn, ?int $tenantId = null): string
```

Defined in [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md), line 1024.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` | `null` | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Called from

- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [ingestInboundMessage()](/code/functions/ingestInboundMessage.md)
- [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md) - in [webchatOpenTicket()](/code/functions/webchatOpenTicket.md)


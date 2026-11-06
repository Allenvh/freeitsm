# webchatIngestMessage()

Ingest one inbound visitor message for a web chat conversation. On the first message it opens a ticket and pins the conversation to it; afterwards it appends. Returns the ticket id. (Thin orchestration over webchatOpenTicket + webchatInsertInbound.)

```php
function webchatIngestMessage(
    PDO $conn,
    array $conversation,
    array $channel,
    string $body,
    ?int &$emailId = null
): int
```

Defined in [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md), line 347.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$conversation` | `array` |  | the webchat_conversations row; its ticket_id decides open or append |
| `$channel` | `array` |  | the widget/channel row |
| `$body` | `string` |  | the visitor's message - untrusted plain text |
| `$emailId` | `?int` | `null` | out: set to the stored inbound emails.id |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 363 | `webchatIngestMessage` | **update** [tickets](/code/tables/tickets.md) (`updated_datetime`, `last_inbound_at`) |  |
| 369 | `webchatIngestMessage` | **update** [webchat_conversations](/code/tables/webchat_conversations.md) (`last_activity_datetime`) |  |

## Calls

[buildChannelSubject()](/code/functions/buildChannelSubject.md), [reopenTicketForCustomerReply()](/code/functions/reopenTicketForCustomerReply.md), [wakeSnoozedTicketOnCustomerReply()](/code/functions/wakeSnoozedTicketOnCustomerReply.md), [webchatInsertInbound()](/code/functions/webchatInsertInbound.md), [webchatOpenTicket()](/code/functions/webchatOpenTicket.md), [webchatVisitorName()](/code/functions/webchatVisitorName.md)

## Called from

- [api/webchat/send.php](/code/files/api/webchat/send.php.md)


# webchatInsertInbound()

Store one inbound visitor message against a ticket in the shared `emails` table (channel='webchat'), so the reading-pane thread and reply path work unchanged. Returns the new email id.

```php
function webchatInsertInbound(
    PDO $conn,
    int $ticketId,
    array $conversation,
    array $channel,
    string $body,
    bool $isInitial
): int
```

Defined in [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md), line 265.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$conversation` | `array` |  | the webchat_conversations row (visitor details and token) |
| `$channel` | `array` |  | the widget/channel row (id and name) |
| `$body` | `string` |  | the visitor's message as plain text; blank is stored as '[empty message]' |
| `$isInitial` | `bool` |  | true for the ticket's opening message, which also gets the subject |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 280 | `webchatInsertInbound` | **insert** [emails](/code/tables/emails.md) (`exchange_message_id`, `subject`, `from_address`, `from_name`, `to_recipients`, `received_datetime`, `body_content`, `body_type`, `has_attachments`, `is_read`, `processed_datetime`, `ticket_id`) |  |
| 300 | `webchatInsertInbound` | **update** [messaging_channels](/code/tables/messaging_channels.md) (`last_inbound_datetime`) |  |

## Calls

[buildChannelSubject()](/code/functions/buildChannelSubject.md), [webchatVisitorName()](/code/functions/webchatVisitorName.md)

## Called from

- [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md) - in [webchatIngestMessage()](/code/functions/webchatIngestMessage.md), [webchatPromoteToTicket()](/code/functions/webchatPromoteToTicket.md)


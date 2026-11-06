# webchatInsertOutbound()

Store an automated/outbound web chat message against a ticket in `emails` (direction='Outbound', channel='webchat'), so the analyst sees it in the thread and the widget's reply-poll picks it up. Used to post the AI's answers alongside the ticket in assist mode. Returns the new email id.

```php
function webchatInsertOutbound(
    PDO $conn,
    int $ticketId,
    array $channel,
    string $body,
    string $fromName
): int
```

Defined in [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md), line 317.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$channel` | `array` |  | the widget/channel row (id and name) |
| `$body` | `string` |  | the message text |
| `$fromName` | `string` |  | the sender name shown in the thread, e.g. the assistant's name |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 320 | `webchatInsertOutbound` | **insert** [emails](/code/tables/emails.md) (`exchange_message_id`, `subject`, `from_address`, `from_name`, `to_recipients`, `received_datetime`, `body_content`, `body_type`, `has_attachments`, `is_read`, `processed_datetime`, `ticket_id`) |  |

## Called from

- [api/webchat/send.php](/code/files/api/webchat/send.php.md)


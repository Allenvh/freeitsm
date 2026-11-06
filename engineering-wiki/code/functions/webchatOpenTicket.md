# webchatOpenTicket()

Open a ticket for a web chat conversation and pin the conversation to it (one conversation == one ticket). Returns the new ticket id. Shared by the live-ingest path and the deflect-mode escalation path so ticket creation lives in one place.

```php
function webchatOpenTicket(PDO $conn, array $conversation, array $channel, string $subject): int
```

Defined in [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md), line 220.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$conversation` | `array` |  | the webchat_conversations row, pinned to the new ticket |
| `$channel` | `array` |  | the widget/channel row (its tenant_id sets the ticket's company) |
| `$subject` | `string` |  | the ticket subject, from `buildChannelSubject()` |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 231 | `webchatOpenTicket` | **insert** [tickets](/code/tables/tickets.md) (`ticket_number`, `subject`, `status_id`, `priority_id`, `created_datetime`, `updated_datetime`, `last_inbound_at`, `user_id`, `tenant_id`, `origin_id`), read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 249 | `webchatOpenTicket` | **update** [webchat_conversations](/code/tables/webchat_conversations.md) (`ticket_id`, `last_activity_datetime`) |  |

## Calls

[getChannelOriginId()](/code/functions/getChannelOriginId.md), [messagingGenerateTicketNumber()](/code/functions/messagingGenerateTicketNumber.md), [ticketSensitivityApplyDefaults()](/code/functions/ticketSensitivityApplyDefaults.md), [webchatGetOrCreateUser()](/code/functions/webchatGetOrCreateUser.md)

## Called from

- [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md) - in [webchatIngestMessage()](/code/functions/webchatIngestMessage.md), [webchatPromoteToTicket()](/code/functions/webchatPromoteToTicket.md)


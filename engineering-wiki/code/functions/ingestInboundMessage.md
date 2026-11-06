# ingestInboundMessage()

Ingest one normalised inbound message for a (decrypted) channel row. Returns ['status' => 'created'|'appended'|'duplicate', 'ticket_id' => int|null].

```php
function ingestInboundMessage(PDO $conn, array $channel, array $msg): array
```

Defined in [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md), line 31.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$channel` | `array` |  | the messaging_channels row, decrypted |
| `$msg` | `array` |  | one normalised message from the provider's `parseInbound()` (from, to, body, profile_name, provider_msg_id, media, and optionally csat, contact, sender_id, language_code, is_test) |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 46 | `ingestInboundMessage` | read [emails](/code/tables/emails.md) |  |
| 161 | `ingestInboundMessage` | **insert** [tickets](/code/tables/tickets.md) (`ticket_number`, `subject`, `status_id`, `priority_id`, `created_datetime`, `updated_datetime`, `last_inbound_at`, `user_id`, `tenant_id`, `origin_id`), read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 180 | `ingestInboundMessage` | **update** [tickets](/code/tables/tickets.md) (`updated_datetime`, `last_inbound_at`) |  |
| 189 | `ingestInboundMessage` | **insert** [emails](/code/tables/emails.md) (`exchange_message_id`, `subject`, `from_address`, `from_name`, `to_recipients`, `received_datetime`, `body_content`, `body_type`, `has_attachments`, `is_read`, `processed_datetime`, `ticket_id`) |  |
| 252 | `ingestInboundMessage` | **update** [emails](/code/tables/emails.md) (`has_attachments`) |  |
| 254 | `ingestInboundMessage` | **update** [emails](/code/tables/emails.md) (`body_content`) |  |
| 261 | `ingestInboundMessage` | **update** [emails](/code/tables/emails.md) (`body_content`) |  |
| 268 | `ingestInboundMessage` | **update** [messaging_channels](/code/tables/messaging_channels.md) (`last_inbound_datetime`) |  |

## Calls

[attachmentIngestNotice()](/code/functions/attachmentIngestNotice.md), [buildChannelSubject()](/code/functions/buildChannelSubject.md), [csatPendingRequestForChat()](/code/functions/csatPendingRequestForChat.md), [findOpenChannelTicket()](/code/functions/findOpenChannelTicket.md), [getChannelOriginId()](/code/functions/getChannelOriginId.md), [getOrCreateChannelUser()](/code/functions/getOrCreateChannelUser.md), [messagingGenerateTicketNumber()](/code/functions/messagingGenerateTicketNumber.md), [messagingProvider()](/code/functions/messagingProvider.md), [messagingRecordCsatReply()](/code/functions/messagingRecordCsatReply.md), [messagingReplyAddress()](/code/functions/messagingReplyAddress.md), [messagingTelegramResolveIdentity()](/code/functions/messagingTelegramResolveIdentity.md), [normaliseChannelIdentifier()](/code/functions/normaliseChannelIdentifier.md), [reopenTicketForCustomerReply()](/code/functions/reopenTicketForCustomerReply.md), [resolveDirectoryRequester()](/code/functions/resolveDirectoryRequester.md), [resolveSlackRequester()](/code/functions/resolveSlackRequester.md), [resolveTicketTenantForChannel()](/code/functions/resolveTicketTenantForChannel.md), [saveChannelMediaAttachment()](/code/functions/saveChannelMediaAttachment.md), [ticketSensitivityApplyDefaults()](/code/functions/ticketSensitivityApplyDefaults.md), [wakeSnoozedTicketOnCustomerReply()](/code/functions/wakeSnoozedTicketOnCustomerReply.md)

## Called from

- [api/messaging/test_channel.php](/code/files/api/messaging/test_channel.php.md) - in [testSimulation()](/code/functions/testSimulation.md)
- [api/messaging/webhook.php](/code/files/api/messaging/webhook.php.md)


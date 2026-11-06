# includes/webchat/webchat.php

> PHP - 566 lines

Web chat widgets — shared helpers.

A web chat widget is the self-hosted twin of a WhatsApp number: it drives one
`messaging_channels` row (channel_type='webchat', provider='freeitsm') so that
once a visitor's message is ingested it flows through the same ticket membrane,
inbox and reply pipeline as every other channel. This file holds the helpers the
config screen and (later) the public widget endpoints share.

  `webchatGenerateKey()`               → a fresh public widget key
  webchatBaseUrl(`$conn`)              → scheme://host + app root for widget URLs
  webchatEmbedSnippet(`$conn`, `$key`)   → the <script> a customer pastes into their site
  webchatLoadByChannel(`$conn`, `$id`)   → a widget row by its channel id (or null)
  webchatLoadByKey(`$conn`, `$key`)      → a widget row by its public key (or null)
  webchatParseOrigins(`$raw`)          → a cleaned list of allowed origins
  webchatOriginAllowed(`$list`, `$orig`) → is this request origin permitted?

The widget key is PUBLIC — it ships in the customer's page source. It is not a
secret: abuse is contained by the origin allowlist + rate limiting, never by
keeping the key hidden.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [webchatGenerateKey()](/code/functions/webchatGenerateKey.md) | 29 | A fresh, URL-safe public widget key (e.g. wc_1a2b…). |
| [webchatOfflineMessage()](/code/functions/webchatOfflineMessage.md) | 41 | The message shown to a visitor when the widget is closed (out of office hours). Uses the admin's custom offline message if set, else a sensible default — so a closed widget is never silent, even if no message was configured. |
| [webchatBaseUrl()](/code/functions/webchatBaseUrl.md) | 55 | Public base URL + app root used to build widget asset / API URLs. Reuses the messaging public base (system setting or derived from the request) and derives the app root from SCRIPT_NAME so it works under any sub-path. Only ever called from a script under /api/webchat/, so that prefix is what we strip. |
| [webchatEmbedSnippet()](/code/functions/webchatEmbedSnippet.md) | 67 | The <script> embed snippet a customer pastes just before </body> on their site. |
| [webchatLoadByChannel()](/code/functions/webchatLoadByChannel.md) | 86 | Load a widget row joined to its channel, keyed by channel id. Null if missing. |
| [webchatLoadByKey()](/code/functions/webchatLoadByKey.md) | 108 | Load a widget row joined to its channel, keyed by public widget key. Null if missing. |
| [webchatParseOrigins()](/code/functions/webchatParseOrigins.md) | 131 | Parse the stored allowed_origins blob into a clean list of origins. Accepts one per line (or comma-separated), trims trailing slashes, drops blanks/comments. |
| [webchatOriginAllowed()](/code/functions/webchatOriginAllowed.md) | 155 | Is the request origin permitted for this widget? An empty allowlist means "any" (testing only). Matching is exact on scheme+host (+port), trailing slash ignored. |
| [webchatGetOrCreateUser()](/code/functions/webchatGetOrCreateUser.md) | 174 | Find-or-create the requester for a web chat conversation, keyed by the real email the visitor gave. Returns the user id, or null if the users table is unavailable. |
| [webchatVisitorName()](/code/functions/webchatVisitorName.md) | 204 | The visitor's display name for a conversation (name → email → generic). |
| [webchatOpenTicket()](/code/functions/webchatOpenTicket.md) | 220 | Open a ticket for a web chat conversation and pin the conversation to it (one conversation == one ticket). Returns the new ticket id. Shared by the live-ingest path and the deflect-mode escalation path so ticket creation lives in one place. |
| [webchatInsertInbound()](/code/functions/webchatInsertInbound.md) | 265 | Store one inbound visitor message against a ticket in the shared `emails` table (channel='webchat'), so the reading-pane thread and reply path work unchanged. Returns the new email id. |
| [webchatInsertOutbound()](/code/functions/webchatInsertOutbound.md) | 317 | Store an automated/outbound web chat message against a ticket in `emails` (direction='Outbound', channel='webchat'), so the analyst sees it in the thread and the widget's reply-poll picks it up. Used to post the AI's answers alongside the ticket in assist mode. Returns the new email id. |
| [webchatIngestMessage()](/code/functions/webchatIngestMessage.md) | 347 | Ingest one inbound visitor message for a web chat conversation. On the first message it opens a ticket and pins the conversation to it; afterwards it appends. Returns the ticket id. (Thin orchestration over webchatOpenTicket + webchatInsertInbound.) |
| [webchatIsOpenNow()](/code/functions/webchatIsOpenNow.md) | 392 | Is the widget "open" right now? A widget with no business-hours calendar (NULL) is always open. Otherwise the calendar's weekday hours + holidays are evaluated in the calendar's own timezone. A misconfigured calendar (missing, bad tz, no hours rows) fails open — availability should never hard-block a visitor from making contact. |
| [webchatAddMessage()](/code/functions/webchatAddMessage.md) | 439 | Append a message to a conversation's pre-ticket transcript (webchat_messages). sender is 'visitor'\|'ai'\|'agent'\|'system'. `$sourceEmailId` links a mirrored analyst reply back to its `emails` row so it's only mirrored once. Returns the new id. |
| [webchatGetMessages()](/code/functions/webchatGetMessages.md) | 458 | Read a conversation's transcript messages after `$afterId` (ascending). |
| [webchatTranscriptText()](/code/functions/webchatTranscriptText.md) | 477 | The full chat transcript as plain text — "Visitor: …" / "Assistant: …" / "Agent: …". Used as the opening message when a deflect conversation is escalated to a ticket, and as the .txt attachment on an email escalation. |
| [webchatMirrorAgentReplies()](/code/functions/webchatMirrorAgentReplies.md) | 496 | Mirror any new analyst replies (Outbound webchat emails on the conversation's ticket) into the transcript, so an AI-enabled widget — which reads webchat_messages, not the emails table — shows them to the visitor. Deduped by source_email_id, so AI answers already recorded with their source email (and previously mirrored replies) are skipped. |
| [webchatPromoteToTicket()](/code/functions/webchatPromoteToTicket.md) | 534 | Promote a deflect-mode conversation to a real ticket. No-op returning the existing id if already ticketed. For `$mode` 'agent' the opening message is the full transcript; for 'email' the opening body is `$aiSummary` (or a fallback) and the full transcript is attached as a .txt. Returns the ticket id. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 90 | `webchatLoadByChannel` | read [webchat_widgets](/code/tables/webchat_widgets.md), read [messaging_channels](/code/tables/messaging_channels.md) |  |
| 112 | `webchatLoadByKey` | read [webchat_widgets](/code/tables/webchat_widgets.md), read [messaging_channels](/code/tables/messaging_channels.md) |  |
| 181 | `webchatGetOrCreateUser` | read [users](/code/tables/users.md) |  |
| 186 | `webchatGetOrCreateUser` | **update** [users](/code/tables/users.md) (`display_name`) |  |
| 191 | `webchatGetOrCreateUser` | **insert** [users](/code/tables/users.md) (`email`, `display_name`, `created_at`) |  |
| 231 | `webchatOpenTicket` | **insert** [tickets](/code/tables/tickets.md) (`ticket_number`, `subject`, `status_id`, `priority_id`, `created_datetime`, `updated_datetime`, `last_inbound_at`, `user_id`, `tenant_id`, `origin_id`), read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 249 | `webchatOpenTicket` | **update** [webchat_conversations](/code/tables/webchat_conversations.md) (`ticket_id`, `last_activity_datetime`) |  |
| 280 | `webchatInsertInbound` | **insert** [emails](/code/tables/emails.md) (`exchange_message_id`, `subject`, `from_address`, `from_name`, `to_recipients`, `received_datetime`, `body_content`, `body_type`, `has_attachments`, `is_read`, `processed_datetime`, `ticket_id`) |  |
| 300 | `webchatInsertInbound` | **update** [messaging_channels](/code/tables/messaging_channels.md) (`last_inbound_datetime`) |  |
| 320 | `webchatInsertOutbound` | **insert** [emails](/code/tables/emails.md) (`exchange_message_id`, `subject`, `from_address`, `from_name`, `to_recipients`, `received_datetime`, `body_content`, `body_type`, `has_attachments`, `is_read`, `processed_datetime`, `ticket_id`) |  |
| 363 | `webchatIngestMessage` | **update** [tickets](/code/tables/tickets.md) (`updated_datetime`, `last_inbound_at`) |  |
| 369 | `webchatIngestMessage` | **update** [webchat_conversations](/code/tables/webchat_conversations.md) (`last_activity_datetime`) |  |
| 442 | `webchatAddMessage` | **insert** [webchat_messages](/code/tables/webchat_messages.md) (`conversation_id`, `sender`, `body`, `source_email_id`, `created_datetime`) |  |
| 447 | `webchatAddMessage` | **update** [webchat_conversations](/code/tables/webchat_conversations.md) (`last_activity_datetime`) |  |
| 461 | `webchatGetMessages` | read [webchat_messages](/code/tables/webchat_messages.md) |  |
| 504 | `webchatMirrorAgentReplies` | read [webchat_messages](/code/tables/webchat_messages.md) |  |
| 509 | `webchatMirrorAgentReplies` | read [emails](/code/tables/emails.md) |  |
| 560 | `webchatPromoteToTicket` | **update** [emails](/code/tables/emails.md) (`has_attachments`) |  |

## How it hangs together

**Includes:** [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), [includes/ticket_reply.php](/code/files/includes/ticket_reply.php.md), [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md), [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md), [includes/sla.php](/code/files/includes/sla.php.md)

**Included by:** [api/webchat/get_widgets.php](/code/files/api/webchat/get_widgets.php.md), [api/webchat/save_widget.php](/code/files/api/webchat/save_widget.php.md), [includes/webchat/public.php](/code/files/includes/webchat/public.php.md)

**Calls:** [buildChannelSubject()](/code/functions/buildChannelSubject.md), [getChannelOriginId()](/code/functions/getChannelOriginId.md), [messagingGenerateTicketNumber()](/code/functions/messagingGenerateTicketNumber.md), [messagingPublicBaseUrl()](/code/functions/messagingPublicBaseUrl.md), [reopenTicketForCustomerReply()](/code/functions/reopenTicketForCustomerReply.md), [saveChannelMediaAttachment()](/code/functions/saveChannelMediaAttachment.md), [sla_load_calendar()](/code/functions/sla_load_calendar.md), [ticketSensitivityApplyDefaults()](/code/functions/ticketSensitivityApplyDefaults.md), [wakeSnoozedTicketOnCustomerReply()](/code/functions/wakeSnoozedTicketOnCustomerReply.md)


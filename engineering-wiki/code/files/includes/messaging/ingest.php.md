# includes/messaging/ingest.php

> PHP - 1071 lines

Channel message ingest — the bridge from a normalised inbound message onto the existing ticket "membrane". This is the channel twin of `saveEmailToDatabase()` in api/tickets/check_mailbox_email.php: find-or-create a ticket, store the message in the shared `emails` table (so the reading-pane thread works for free), route the company, and keep the 24h window state current.

Deliberately self-contained (no dependency on the email importer's internals)
so it is safe to include from the webhook with just functions.php + tenancy.php
+ messaging.php loaded.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [ingestInboundMessage()](/code/functions/ingestInboundMessage.md) | 31 | Ingest one normalised inbound message for a (decrypted) channel row. Returns ['status' => 'created'\|'appended'\|'duplicate', 'ticket_id' => int\|null]. |
| [findOpenChannelTicket()](/code/functions/findOpenChannelTicket.md) | 293 | Find the most recent OPEN (not closed, not trashed) ticket for this conversation, so repeat messages thread into one ticket. |
| [resolveSlackRequester()](/code/functions/resolveSlackRequester.md) | 346 | Work out who a Slack message is from. Returns [userId\|null, displayName]. |
| [messagingKnownPersonByEmail()](/code/functions/messagingKnownPersonByEmail.md) | 408 | The person FreeITSM already holds with this email: [userId, displayName], or null. A real person's chat tickets then sit with their emailed ones, their history and their company. Shared by Slack, Teams and Mattermost. |
| [resolveDirectoryRequester()](/code/functions/resolveDirectoryRequester.md) | 446 | Who a Teams or Mattermost message is from. Returns [userId\|null, displayName]. |
| [messagingIdentityLinksReady()](/code/functions/messagingIdentityLinksReady.md) | 522 | Has Database Verification created messaging_identity_links yet? Cached per request. |
| [messagingTelegramPlaceholderEmail()](/code/functions/messagingTelegramPlaceholderEmail.md) | 540 | The placeholder's synthetic address - MUST match `getOrCreateChannelUser()`. |
| [messagingTelegramIsPlaceholder()](/code/functions/messagingTelegramIsPlaceholder.md) | 551 | Is this user id this chat's own placeholder (and not a real person)? |
| [messagingTelegramResolveIdentity()](/code/functions/messagingTelegramResolveIdentity.md) | 571 | Resolve (never blocks) which `users` row this chat belongs to on this channel. |
| [messagingTelegramLinkPhone()](/code/functions/messagingTelegramLinkPhone.md) | 669 | A phone number has just arrived for this chat. Decide what it means, safely. |
| [messagingTelegramAudit()](/code/functions/messagingTelegramAudit.md) | 775 | Record what happened on the ticket's audit trail (Audit tab), as automation: analyst_id NULL is the established "nobody did this, the system did" marker (see the ticket_audit comment in freeitsm.sql). Best-effort. |
| [messagingUserLabel()](/code/functions/messagingUserLabel.md) | 791 | "Alice Real <alice@example.com>" - for audit entries. |
| [messagingRecordCsatReply()](/code/functions/messagingRecordCsatReply.md) | 812 | Record a CSAT rating that came in from a customer's chat, then thank them. A rating only counts if its request was sent to THIS customer on THIS channel and has not been answered yet, so a replayed or forged button press changes nothing. Never creates or touches a ticket. |
| [messagingTelegramAck()](/code/functions/messagingTelegramAck.md) | 872 | Best-effort reply to the chat. A failed send must never break ingest. |
| [messagingFindUsersByPhone()](/code/functions/messagingFindUsersByPhone.md) | 908 | Every active person whose phone or mobile is this number, compared digits-only so "+44 7700 900123" and "447700900123" agree. Returns user ids, deduplicated. |
| [getOrCreateChannelUser()](/code/functions/getOrCreateChannelUser.md) | 950 | Get-or-create a placeholder requester keyed by phone, so repeat senders map to one user. The users table requires an email, so we synthesise a stable non-routable address ('+44…@whatsapp.local'); the real identity is the display name (the WhatsApp profile name) shown on the ticket. |
| [getChannelOriginId()](/code/functions/getChannelOriginId.md) | 978 | The origin id for a channel type (e.g. the seeded 'WhatsApp' origin), or null. |
| [buildChannelSubject()](/code/functions/buildChannelSubject.md) | 998 | A short, human ticket subject from the first line of the first message. |
| [messagingGenerateTicketNumber()](/code/functions/messagingGenerateTicketNumber.md) | 1024 | The number for a ticket opened by a chat (WhatsApp, Telegram, Slack, Teams, Mattermost, web chat). |
| [saveChannelMediaAttachment()](/code/functions/saveChannelMediaAttachment.md) | 1043 | Stores one downloaded media file as an attachment on the channel message's email row. |

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
| 308 | `findOpenChannelTicket` | read [tickets](/code/tables/tickets.md), read [emails](/code/tables/emails.md) |  |
| 385 | `resolveSlackRequester` | **update** [users](/code/tables/users.md) (`display_name`) |  |
| 414 | `messagingKnownPersonByEmail` | read [users](/code/tables/users.md) |  |
| 475 | `resolveDirectoryRequester` | **update** [users](/code/tables/users.md) (`display_name`) |  |
| 527 | `messagingIdentityLinksReady` | read [messaging_identity_links](/code/tables/messaging_identity_links.md) |  |
| 553 | `messagingTelegramIsPlaceholder` | read [users](/code/tables/users.md) |  |
| 588 | `messagingTelegramResolveIdentity` | read [messaging_identity_links](/code/tables/messaging_identity_links.md) |  |
| 600 | `messagingTelegramResolveIdentity` | **insert** [messaging_identity_links](/code/tables/messaging_identity_links.md) (`channel_id`, `channel_type`, `external_id`, `user_id`, `phone`, `locale`) |  |
| 642 | `messagingTelegramResolveIdentity` | **update** [messaging_identity_links](/code/tables/messaging_identity_links.md) (`locale`) |  |
| 676 | `messagingTelegramLinkPhone` | **update** [messaging_identity_links](/code/tables/messaging_identity_links.md) (`phone`) |  |
| 701 | `messagingTelegramLinkPhone` | **update** [messaging_identity_links](/code/tables/messaging_identity_links.md) (`user_id`) |  |
| 709 | `messagingTelegramLinkPhone` | read [tickets](/code/tables/tickets.md), read [emails](/code/tables/emails.md) |  |
| 717 | `messagingTelegramLinkPhone` | **update** [tickets](/code/tables/tickets.md) (`user_id`) |  |
| 724 | `messagingTelegramLinkPhone` | read [tickets](/code/tables/tickets.md), read [messaging_identity_links](/code/tables/messaging_identity_links.md) |  |
| 727 | `messagingTelegramLinkPhone` | **delete** [users](/code/tables/users.md) |  |
| 745 | `messagingTelegramLinkPhone` | read [users](/code/tables/users.md) |  |
| 753 | `messagingTelegramLinkPhone` | **update** [users](/code/tables/users.md) (`mobile`) |  |
| 778 | `messagingTelegramAudit` | **insert** [ticket_audit](/code/tables/ticket_audit.md) (`ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |
| 793 | `messagingUserLabel` | read [users](/code/tables/users.md) |  |
| 914 | `messagingFindUsersByPhone` | read [users](/code/tables/users.md) |  |
| 958 | `getOrCreateChannelUser` | read [users](/code/tables/users.md) |  |
| 964 | `getOrCreateChannelUser` | **insert** [users](/code/tables/users.md) (`email`, `display_name`, `created_at`) |  |
| 982 | `getChannelOriginId` | read [ticket_origins](/code/tables/ticket_origins.md) |  |
| 1065 | `saveChannelMediaAttachment` | **insert** [email_attachments](/code/tables/email_attachments.md) (`email_id`, `exchange_attachment_id`, `filename`, `content_type`, `content_id`, `file_path`, `file_size`, `is_inline`) |  |

## How it hangs together

**Includes:** [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/ticket_reply.php](/code/files/includes/ticket_reply.php.md), [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md), [includes/uploads.php](/code/files/includes/uploads.php.md), [includes/csat.php](/code/files/includes/csat.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/ticket_numbering.php](/code/files/includes/ticket_numbering.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md)

**Included by:** [api/messaging/test_channel.php](/code/files/api/messaging/test_channel.php.md), [api/messaging/webhook.php](/code/files/api/messaging/webhook.php.md), [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md)

**Calls:** [attachmentAllowedTypes()](/code/functions/attachmentAllowedTypes.md), [attachmentIngestNotice()](/code/functions/attachmentIngestNotice.md), [attachmentRejectPolicy()](/code/functions/attachmentRejectPolicy.md), [csatChannelLocale()](/code/functions/csatChannelLocale.md), [csatPendingRequestForChat()](/code/functions/csatPendingRequestForChat.md), [csatRecordRating()](/code/functions/csatRecordRating.md), [csatResponseBelongsToChat()](/code/functions/csatResponseBelongsToChat.md), [csatStoredRating()](/code/functions/csatStoredRating.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [messagingNormaliseLocale()](/code/functions/messagingNormaliseLocale.md), [messagingProvider()](/code/functions/messagingProvider.md), [messagingReplyAddress()](/code/functions/messagingReplyAddress.md), [normaliseChannelIdentifier()](/code/functions/normaliseChannelIdentifier.md), [reopenTicketForCustomerReply()](/code/functions/reopenTicketForCustomerReply.md), [resolveTicketTenantForChannel()](/code/functions/resolveTicketTenantForChannel.md), [ticketSensitivityApplyDefaults()](/code/functions/ticketSensitivityApplyDefaults.md), [uploadStoreBytes()](/code/functions/uploadStoreBytes.md), [wakeSnoozedTicketOnCustomerReply()](/code/functions/wakeSnoozedTicketOnCustomerReply.md)

## Traps

> [!WARNING]
> **Line 302, in `findOpenChannelTicket`:** a new channel type whose sender id is not unique per channel must be added here. Mattermost (PR #166) is one: a user id is the same in every configuration on one server, so two companies' support channels would share a person's thread - another company's ticket - without this.

> [!WARNING]
> **Line 394:** only ever pass an address the PLATFORM vouches for - Slack's profile, Microsoft 365's directory, Mattermost's verified flag. The email is the whole of the identity here: an address someone merely typed would file their chats under whoever owns it. users.email is unique, so a match is one person, never a guess between two.

> [!WARNING]
> **Line 1013:** never make a ticket number here, or anywhere else, by hand. This used to roll its own XXX-NNN-NNNNN, so on an install set to TICKET-{######} every chat ticket still came out as JNL-471-47705 (GH #71 moved three generators onto TicketNumbering and missed this one, plus catalogue approvals, merge and split). Pass the ticket's company: per-company numbering and {COMPANY} need it.


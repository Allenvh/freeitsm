# api/messaging/send_attachment.php

> API endpoint - 186 lines

API Endpoint: analyst sends a file (image, document, …) out over a messaging channel from the ticket reading pane.

Contributed in PR #159 (turbay-a); reworked at merge time - see the wiki page
Telegram-Channel-Developer-Guide, "Sending attachments".

The file twin of send_message.php — same recipient/window/channel
resolution, but multipart/form-data (ticket_id + file + optional caption)
instead of a JSON body, because a browser cannot put a file into JSON.

The DB row + final attachment file are created BEFORE the provider is
asked to send — unlike send_message.php, which sends first and records
after. Twilio needs a URL to fetch the bytes FROM (it never takes an
upload), and that URL has to point at something that already exists at its
final path; Meta and Telegram upload the bytes directly and would be fine
with the old order, but one shared order for every provider is less to get
wrong than a branch per provider. A failed send - or a file that fails
validation - rolls all of it back, so nothing lingers in the thread looking
like it went out.

TelegramProvider, MetaCloudProvider and TwilioProvider implement
MessagingProvider::sendMedia(); SlackProvider and FreeitsmProvider (web chat)
don't yet — their base-class default throws "not supported for this channel
yet", which this endpoint surfaces as an ordinary error, not a 500.

## The endpoint

| | |
|---|---|
| URL | `api/messaging/send_attachment.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `POST ticket_id`, `POST caption` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 70 |  | read [emails](/code/tables/emails.md) |  |
| 100 |  | read [tickets](/code/tables/tickets.md) |  |
| 110 |  | **insert** [emails](/code/tables/emails.md) (`exchange_message_id`, `subject`, `from_address`, `from_name`, `to_recipients`, `received_datetime`, `body_content`, `body_type`, `has_attachments`, `ticket_id`, `is_initial`, `direction`) |  |
| 145 |  | **insert** [email_attachments](/code/tables/email_attachments.md) (`email_id`, `exchange_attachment_id`, `filename`, `content_type`, `content_id`, `file_path`, `file_size`, `is_inline`) |  |
| 164 |  | **update** [emails](/code/tables/emails.md) (`exchange_message_id`) |  |
| 166 |  | **update** [tickets](/code/tables/tickets.md) (`updated_datetime`) |  |
| 177 |  | **delete** [email_attachments](/code/tables/email_attachments.md) |  |
| 178 |  | **delete** [emails](/code/tables/emails.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), [includes/uploads.php](/code/files/includes/uploads.php.md)

**Calls:** [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [attachmentAllowedTypes()](/code/functions/attachmentAllowedTypes.md), [channelHasServiceWindow()](/code/functions/channelHasServiceWindow.md), [channelWindowOpen()](/code/functions/channelWindowOpen.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [loadMessagingChannel()](/code/functions/loadMessagingChannel.md), [messagingOutboundMediaUrl()](/code/functions/messagingOutboundMediaUrl.md), [messagingProvider()](/code/functions/messagingProvider.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [uploadStoreFile()](/code/functions/uploadStoreFile.md)


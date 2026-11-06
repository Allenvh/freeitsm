# api/messaging/send_message.php

> API endpoint - 126 lines

API Endpoint: analyst reply on a messaging channel (WhatsApp etc.).

The channel twin of api/tickets/send_email.php. Sends the analyst's text out via
the ticket's channel provider, then stores the outbound message in the shared
`emails` table so it appears in the reading-pane thread next to the inbound ones.

Refuses to send outside the provider 24h service window (free-text is blocked
there — only template messages are allowed, which is Phase 3).

## The endpoint

| | |
|---|---|
| URL | `api/messaging/send_message.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 50 |  | read [emails](/code/tables/emails.md) |  |
| 89 |  | read [tickets](/code/tables/tickets.md) |  |
| 102 |  | **insert** [emails](/code/tables/emails.md) (`exchange_message_id`, `subject`, `from_address`, `from_name`, `to_recipients`, `received_datetime`, `body_content`, `body_type`, `ticket_id`, `is_initial`, `direction`, `channel`) |  |
| 119 |  | **update** [tickets](/code/tables/tickets.md) (`updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md)

**Calls:** [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [channelHasServiceWindow()](/code/functions/channelHasServiceWindow.md), [channelWindowOpen()](/code/functions/channelWindowOpen.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [loadMessagingChannel()](/code/functions/loadMessagingChannel.md), [messagingProvider()](/code/functions/messagingProvider.md), [messagingReplyAddress()](/code/functions/messagingReplyAddress.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


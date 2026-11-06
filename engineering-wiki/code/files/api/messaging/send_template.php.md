# api/messaging/send_template.php

> API endpoint - 103 lines

API Endpoint: send a pre-approved template message on a channel ticket.

This is how an analyst re-opens a conversation after the 24-hour window has
closed, so (unlike send_message.php) it deliberately does NOT require the window
to be open. The customer's reply to the template reopens the free-text window.

## The endpoint

| | |
|---|---|
| URL | `api/messaging/send_template.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 41 |  | read [emails](/code/tables/emails.md) |  |
| 57 |  | read [messaging_templates](/code/tables/messaging_templates.md) |  |
| 79 |  | **insert** [emails](/code/tables/emails.md) (`exchange_message_id`, `subject`, `from_address`, `from_name`, `to_recipients`, `received_datetime`, `body_content`, `body_type`, `ticket_id`, `is_initial`, `direction`, `channel`) |  |
| 96 |  | **update** [tickets](/code/tables/tickets.md) (`updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md)

**Calls:** [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [loadMessagingChannel()](/code/functions/loadMessagingChannel.md), [messagingProvider()](/code/functions/messagingProvider.md), [messagingRenderTemplate()](/code/functions/messagingRenderTemplate.md), [messagingTemplateVarCount()](/code/functions/messagingTemplateVarCount.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


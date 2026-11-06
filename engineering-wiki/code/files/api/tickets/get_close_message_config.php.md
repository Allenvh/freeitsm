# api/tickets/get_close_message_config.php

> API endpoint - 54 lines

API Endpoint: should closing a ticket offer a one-off message? (#142)

GET -> { success: true, enabled: bool }

🔑 The prompt appears ONLY when an operator has actually put
[ticket_closed_message] into an active "Ticket closed" template. Two reasons,
and both are about not wasting the analyst's attention:

 - Without the placeholder, anything typed is silently discarded. The person
   closing the ticket has no way of knowing whether the template was ever
   customised, so the safe thing is not to ask.
 - Every install that has not configured this would otherwise gain an extra
   click on every single close, forever.

⚠️ Deliberately NOT recipient-aware. `templateSelectForRecipient()` picks a
template from the requester's address, so strictly the right answer varies by
ticket. But this runs before the analyst has committed to closing, the
difference only matters on installs using per-sender template rules, and the
failure it would cause (a prompt that turns out not to be used) is far milder
than its opposite (no prompt where one was wanted). Any ACTIVE template for
the event carrying the code is enough to offer it.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_close_message_config.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 40 |  | read [ticket_email_templates](/code/tables/ticket_email_templates.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


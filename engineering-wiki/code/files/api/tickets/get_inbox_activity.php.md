# api/tickets/get_inbox_activity.php

> API endpoint - 51 lines

API Endpoint: has anything new arrived? - one number, for the inbox to poll. GET → { success, marker }

`marker` is the id of the newest message (email, chat message, reply or note
row in `emails`) on any ticket this analyst can see. The inbox polls it every
minute alongside its own mailbox check, and when it goes up runs the same
refresh as the ↻ button.

Why it exists: the inbox only refreshed when the mailbox check IT ran found
mail. A WhatsApp, Telegram, Slack, Teams or Mattermost message arrives at the
server through a webhook, and mail collected by the scheduled task arrives
without the browser - neither ever told an open inbox, so a new chat ticket
sat unseen until somebody pressed refresh.

Deliberately says nothing but a number: no subject, no sender, no count, so it
reveals nothing a refresh would not show anyway. Scoped to the analyst's
companies, so another company's traffic never makes this inbox refresh.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_inbox_activity.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 41 |  | read [emails](/code/tables/emails.md), read [tickets](/code/tables/tickets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ticketTenantFilter()](/code/functions/ticketTenantFilter.md)


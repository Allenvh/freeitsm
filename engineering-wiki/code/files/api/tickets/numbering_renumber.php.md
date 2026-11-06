# api/tickets/numbering_renumber.php

> API endpoint - 60 lines

API: renumber existing tickets into the current scheme — preview or live.

🔴 THIS IS A MIGRATION TOOL, NOT HOUSEKEEPING. It rewrites the reference on
every ticket, and those references are quoted in emails, change records,
knowledge articles and customers' own spreadsheets.

All of the thinking lives in TicketNumbering::planRenumber() and
::applyRenumber() so that it can be tested — see tests/ticket-numbering.php.
This file is only the door: authenticate, plan, and either describe the plan
or carry it out. The preview and the live run share ONE code path, so what
somebody is shown is exactly what would happen.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/numbering_renumber.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_NUMBERING (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/ticket_numbering.php](/code/files/includes/ticket_numbering.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


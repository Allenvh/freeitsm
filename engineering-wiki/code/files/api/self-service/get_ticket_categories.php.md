# api/self-service/get_ticket_categories.php

> API endpoint - 68 lines

API Endpoint (portal): the categories a REQUESTER may choose from (#1540).

Deliberately narrower than the analyst endpoint in three ways:

  1. `is_portal_visible` only. Analysts see the whole tree; a customer should
     see a short list in plain English, not sixty internal leaves.
  2. Active only, and a category whose ANCESTOR is retired or hidden goes with
     it — otherwise a child surfaces at the top level wearing a path that no
     longer resolves.
  3. Untied categories only. The portal form has no ticket type field at all,
     so a category tied to one has no context here and offering it would let a
     requester pick "Incident / Printing" on a ticket that is not an Incident.

Also returns whether the field is switched on for this user's company, so the
form can decide whether to render it without a second request.

## The endpoint

| | |
|---|---|
| URL | `api/self-service/get_ticket_categories.php` |
| Protected by | Signed-in portal user (session check) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 42 |  | read [users](/code/tables/users.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md), [includes/ticket_categories.php](/code/files/includes/ticket_categories.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [ticketCategoriesResolved()](/code/functions/ticketCategoriesResolved.md), [ticketCategoryOn()](/code/functions/ticketCategoryOn.md)


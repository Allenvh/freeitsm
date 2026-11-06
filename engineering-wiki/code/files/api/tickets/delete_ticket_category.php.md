# api/tickets/delete_ticket_category.php

> API endpoint - 103 lines

API Endpoint: delete a ticket category (#1540).

⚠️ DELETING IS THE EXCEPTION, NOT THE NORMAL WAY TO REMOVE ONE.

A category is meant to be RETIRED — is_active = 0 — so that five years of
closed tickets keep the label they were closed with. Deleting is only allowed
while nothing points at it, and this endpoint checks three things first:

  1. no sub-categories underneath it
  2. no ticket using it as its category
  3. no ticket using it as its CLOSURE category

(2) and (3) are separate checks because they are separate columns and a
category can easily be in use as one and not the other — a category that was
only ever picked at close would sail past a check that looked at category_id
alone, and the delete would then fail on a foreign key with an error nobody
can act on.

The foreign keys carry no ON DELETE action, so MySQL RESTRICTs and would stop
this anyway. The checks exist to say WHY, in words, before that happens.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/delete_ticket_category.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_CATEGORIES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 53 |  | read [ticket_categories](/code/tables/ticket_categories.md) |  |
| 67 |  | read [ticket_categories](/code/tables/ticket_categories.md) |  |
| 77 |  | read [tickets](/code/tables/tickets.md) |  |
| 95 |  | **delete** [ticket_categories](/code/tables/ticket_categories.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/ticket_categories.php](/code/files/includes/ticket_categories.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)


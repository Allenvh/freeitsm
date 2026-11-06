# api/tickets/save_ticket_category.php

> API endpoint - 179 lines

API Endpoint: create or update a ticket category (#1540).

POST { id?, name, description?, parent_id?, ticket_type_id?,
       is_portal_visible?, is_active?, display_order? }

---------------------------------------------------------------------------
THE THREE RULES THE SCHEMA CANNOT ENFORCE
---------------------------------------------------------------------------
1. DEPTH. Capped at TICKET_CATEGORY_MAX_DEPTH. An unbounded tree is
   unreportable — nobody reads a chart with two hundred leaves and the roll-up
   has no level to stop at.

2. THE TYPE LINK LIVES ON THE ROOT. `ticket_type_id` is refused on a child,
   which inherits its root's. If a sub-category could name its own type,
   "Hardware → Printer" could be an Incident while "Hardware" was a Service
   request, and neither answer would be wrong.

3. NO CYCLES. Re-parenting a category under its own descendant makes every
   path unresolvable and every roll-up infinite.

⚠️ And one the DB only half-covers: the unique key is
(tenant_id, parent_id, name), but MySQL allows unlimited NULLs in a unique
key — so two GLOBAL roots with the same name would both slip straight through
it. Sibling-name uniqueness is enforced here, in the query below. The same
split ticket_types has always lived with.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_ticket_category.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_CATEGORIES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 76 |  | read [ticket_categories](/code/tables/ticket_categories.md) |  |
| 109 |  | read [ticket_types](/code/tables/ticket_types.md) |  |
| 115 |  | read [ticket_categories](/code/tables/ticket_categories.md) |  |
| 130 |  | read [ticket_categories](/code/tables/ticket_categories.md) |  |
| 157 |  | **update** [ticket_categories](/code/tables/ticket_categories.md) (`name`, `description`, `parent_id`, `ticket_type_id`, `is_portal_visible`, `is_active`, `display_order`) |  |
| 166 |  | **insert** [ticket_categories](/code/tables/ticket_categories.md) (`name`, `description`, `parent_id`, `ticket_type_id`, `is_portal_visible`, `is_active`, `display_order`, `tenant_id`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/ticket_categories.php](/code/files/includes/ticket_categories.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ticketCategoryDepthUnder()](/code/functions/ticketCategoryDepthUnder.md), [ticketCategorySubtreeDepth()](/code/functions/ticketCategorySubtreeDepth.md), [ticketCategoryWouldCycle()](/code/functions/ticketCategoryWouldCycle.md), [wf_emit()](/code/functions/wf_emit.md)


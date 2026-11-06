# includes/ticket_categories.php

> PHP - 368 lines

Ticket categories and resolution codes (#1540) — the shared reader.

---------------------------------------------------------------------------
THE ONE RULE THIS FILE EXISTS TO ENFORCE
---------------------------------------------------------------------------
A ticket stores the LEAF category id and nothing else. There is no
`parent_category_id` on `tickets` and there must never be one. Every ancestor
— the path shown on screen, the root used for roll-up reporting, the ticket
type a category belongs to — is DERIVED here, from `parent_id`.

That is not tidiness. `tickets` already stores the assignee twice, in
`assigned_analyst_id` and `owner_id`, and on a real installation 93 of 110
rows disagree with themselves. Two columns holding one fact will always end
up holding two different facts. So: one column, derived ancestors, always.

---------------------------------------------------------------------------
THE TYPE LINK LIVES ON THE ROOT
---------------------------------------------------------------------------
`ticket_type_id` is only meaningful where `parent_id IS NULL`. A child
inherits its root's type, and saving a type onto a child is refused. If a
sub-category could name its own type, "Hardware → Printer" could be an
Incident while "Hardware" was a Service request, and neither answer would be
wrong — which means neither would be usable.

---------------------------------------------------------------------------
DEPTH
---------------------------------------------------------------------------
Capped at MAX_DEPTH (3), in code rather than in the schema. An unbounded tree
is unreportable: nobody can read a chart with two hundred leaves on it, and
the roll-up has no natural level to stop at. Three is what the tools that do
this well settle on (Category → Subcategory → Item, where the item is the
actual noun: Hardware → Printer → Toner).

## Functions

| Function | Line | What it does |
|---|--:|---|
| [ticketCategoriesResolved()](/code/functions/ticketCategoriesResolved.md) | 58 | Every category this company can see, each with its derived path and root. |
| [ticketCategorySortTree()](/code/functions/ticketCategorySortTree.md) | 164 | Order a resolved set depth-first: each root, then its descendants, by display_order then name at every level. |
| [ticketCategoryPathLabel()](/code/functions/ticketCategoryPathLabel.md) | 200 | One category's full path, for display next to a ticket. |
| [ticketCategoryDepthUnder()](/code/functions/ticketCategoryDepthUnder.md) | 233 | The depth a NEW child of `$parentId` would sit at, 1 for a root. |
| [ticketCategorySubtreeDepth()](/code/functions/ticketCategorySubtreeDepth.md) | 262 | How many levels the subtree rooted at `$id` occupies (1 = no children). |
| [ticketCategoryWouldCycle()](/code/functions/ticketCategoryWouldCycle.md) | 289 | Would making `$parentId` the parent of `$id` create a loop? |
| [ticketResolutionCodesResolved()](/code/functions/ticketResolutionCodesResolved.md) | 316 | Resolution codes this company can see. |
| [ticketResolutionCodeName()](/code/functions/ticketResolutionCodeName.md) | 354 | One resolution code's name, ignoring is_active. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 74 | `ticketCategoriesResolved` | read [ticket_categories](/code/tables/ticket_categories.md) |  |
| 210 | `ticketCategoryPathLabel` | read [ticket_categories](/code/tables/ticket_categories.md) |  |
| 239 | `ticketCategoryDepthUnder` | read [ticket_categories](/code/tables/ticket_categories.md) |  |
| 266 | `ticketCategorySubtreeDepth` | read [ticket_categories](/code/tables/ticket_categories.md) |  |
| 296 | `ticketCategoryWouldCycle` | read [ticket_categories](/code/tables/ticket_categories.md) |  |
| 325 | `ticketResolutionCodesResolved` | read [ticket_resolution_codes](/code/tables/ticket_resolution_codes.md) |  |
| 360 | `ticketResolutionCodeName` | read [ticket_resolution_codes](/code/tables/ticket_resolution_codes.md) |  |

## How it hangs together

**Includes:** [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Included by:** [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md), [api/self-service/get_ticket_categories.php](/code/files/api/self-service/get_ticket_categories.php.md), [api/tickets/delete_ticket_category.php](/code/files/api/tickets/delete_ticket_category.php.md), [api/tickets/get_ticket_classification.php](/code/files/api/tickets/get_ticket_classification.php.md), [api/tickets/save_ticket_category.php](/code/files/api/tickets/save_ticket_category.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md)

**Calls:** [getTenantConfigRows()](/code/functions/getTenantConfigRows.md)


# includes/ticket_links.php

> PHP - 283 lines

Ticket-to-ticket linking (issue #38). Self-referential, typed links stored in `ticket_links`. Three stored relation types: 'related' — symmetric (order irrelevant; reciprocal duplicates blocked) 'duplicate' — source is a DUPLICATE OF target (target is the master) 'parent' — source is the PARENT OF target (target is the child)

The UI expresses direction from the acting ticket's point of view with four
choices (related / duplicate_of / parent_of / child_of); we canonicalise to
the three stored types (child_of is stored as the inverse 'parent').

v1 is informational only — links display and are audited, nothing changes
ticket state automatically. Multi-tenancy: links are same-company only.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [ticketLinkLoad()](/code/functions/ticketLinkLoad.md) | 24 | Load the minimal ticket row we need (or null if missing/deleted). |
| [ticketLinkNum()](/code/functions/ticketLinkNum.md) | 37 | A ticket's number for audit text, falling back to "#id" when it has none (deleted tickets included). |
| [ticketLinkAudit()](/code/functions/ticketLinkAudit.md) | 52 | Writes one ticket_audit row for a link change, with no old value and the new value cut to 500 characters. |
| [ticketLinkCreate()](/code/functions/ticketLinkCreate.md) | 68 | Create a link. `$uiRelation` is one of related\|duplicate_of\|parent_of\|child_of, expressed from `$sourceId`'s perspective. Returns ['success'=>bool, 'error'=>?]. |
| [ticketLinkRemove()](/code/functions/ticketLinkRemove.md) | 184 | Remove a link by id. |
| [ticketLinksFor()](/code/functions/ticketLinksFor.md) | 211 | All of a ticket's links, grouped for display from THIS ticket's perspective. Returns parent (0/1), children[], duplicate_of (0/1), duplicates[], related[]. |
| [ticketLinkableList()](/code/functions/ticketLinkableList.md) | 255 | Ticket search for the link picker — scoped to the source ticket's company. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 26 | `ticketLinkLoad` | read [tickets](/code/tables/tickets.md) |  |
| 39 | `ticketLinkNum` | read [tickets](/code/tables/tickets.md) |  |
| 55 | `ticketLinkAudit` | **insert** [ticket_audit](/code/tables/ticket_audit.md) (`ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |
| 115 | `ticketLinkCreate` | read [ticket_links](/code/tables/ticket_links.md) |  |
| 124 | `ticketLinkCreate` | read [ticket_links](/code/tables/ticket_links.md) |  |
| 131 | `ticketLinkCreate` | read [ticket_links](/code/tables/ticket_links.md) |  |
| 141 | `ticketLinkCreate` | read [ticket_links](/code/tables/ticket_links.md) |  |
| 151 | `ticketLinkCreate` | read [ticket_links](/code/tables/ticket_links.md) |  |
| 158 | `ticketLinkCreate` | **insert** [ticket_links](/code/tables/ticket_links.md) (`source_ticket_id`, `target_ticket_id`, `relation_type`, `created_by_id`, `created_datetime`) |  |
| 186 | `ticketLinkRemove` | read [ticket_links](/code/tables/ticket_links.md) |  |
| 199 | `ticketLinkRemove` | **delete** [ticket_links](/code/tables/ticket_links.md) |  |
| 214 | `ticketLinksFor` | read [ticket_links](/code/tables/ticket_links.md), read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 276 | `ticketLinkableList` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |

## How it hangs together

**Includes:** [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Included by:** [api/tickets/create_ticket_link.php](/code/files/api/tickets/create_ticket_link.php.md), [api/tickets/delete_ticket_link.php](/code/files/api/tickets/delete_ticket_link.php.md), [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md), [api/tickets/list_linkable_tickets.php](/code/files/api/tickets/list_linkable_tickets.php.md)

**Calls:** [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)


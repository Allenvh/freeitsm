# includes/services/mandatory_fields.php

> PHP - 303 lines

Mandatory fields at closure.

An administrator ticks the ticket properties that must hold a value before a
ticket may close (Tickets → Settings → Mandatory fields), and chooses what
happens when a close arrives with some of them empty:

  warn     the analyst is told and may close anyway
  notify   the same, and the addresses on the tab are emailed
  block    the close is refused until the fields are filled

plus a separate "record it" switch that writes an internal note naming the
empty fields and who closed the ticket. Recording only applies to the two
warn modes: under block the ticket never closes, so there is nothing to record.

---------------------------------------------------------------------------
WHERE IT IS ENFORCED
---------------------------------------------------------------------------
TicketsService::updateTicket() - the inbox, bulk actions, assign/schedule and
the v1 REST API all close tickets through it - and the workflow engine's
set-status action, which writes the status itself. The check runs BEFORE the
write, against the ticket as it will be AFTER the save, so a value filled in by
the same request that closes the ticket counts.

Deliberately NOT enforced on a merge. Merging closes the tickets merged away,
and refusing a merge because a ticket that is about to disappear into another
one has no category helps nobody.

---------------------------------------------------------------------------
A FIELD THE TICKET CANNOT SHOW IS NEVER REQUIRED
---------------------------------------------------------------------------
Category, category at close and resolution code each have their own on/off
switch per company, and the team picker only exists on an install that has
teams. Requiring a field the analyst has no way to fill would trap every
ticket behind it, so such a field is skipped for that ticket.

## Classes

- [MandatoryFieldsService](/code/classes/MandatoryFieldsService.md) (class, line 41)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 164 | `MandatoryFieldsService::fieldAvailable` | read [teams](/code/tables/teams.md) |  |
| 243 | `MandatoryFieldsService::writeNote` | **insert** [ticket_notes](/code/tables/ticket_notes.md) (`ticket_id`, `analyst_id`, `note_text`, `is_internal`, `created_datetime`) |  |
| 249 | `MandatoryFieldsService::writeNote` | **insert** [ticket_audit](/code/tables/ticket_audit.md) (`ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |

## How it hangs together

**Includes:** [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md), [includes/template_email.php](/code/files/includes/template_email.php.md)

**Included by:** [api/tickets/save_mandatory_fields_settings.php](/code/files/api/tickets/save_mandatory_fields_settings.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md), [tickets/settings/index.php](/code/files/tickets/settings/index.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md)

**Calls:** [buildTicketMergeData()](/code/functions/buildTicketMergeData.md), [internalTicketEmail()](/code/functions/internalTicketEmail.md), [tenantSetting()](/code/functions/tenantSetting.md), [tenantSettingOn()](/code/functions/tenantSettingOn.md), [ticketCategoryOn()](/code/functions/ticketCategoryOn.md), [ticketClosureCategoryOn()](/code/functions/ticketClosureCategoryOn.md), [ticketResolutionCodeOn()](/code/functions/ticketResolutionCodeOn.md)


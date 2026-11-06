# includes/services/tickets.php

> PHP - 1321 lines

TicketsService — the shared write rules for tickets: create, the unified update (status/priority/lookups/assignment/booleans/schedule/company-move, with closed_datetime, owner sync, template emails, CSAT auto-trigger and the workflow dispatches), soft delete + restore, notes, and time entries.

Shared by the UI endpoints (api/tickets/*.php) and the REST API
(api/v1/resources/tickets.php). This is the last + biggest module.

TENANCY — tickets are company-scoped (tickets.tenant_id). Every by-id write is
gated by the actor's company scope via ctx->companyScope (null = all), the
same generic check problems #733 established (canAccessTicket mirrors
apiKeyCanAccessTenantRow / analystCanAccessTicket exactly). The acting company
for a create / company-move is resolved by the adapter and passed in.

TRANSPORT-SPECIFIC BEHAVIOUR is preserved via parameters, so the API stays
byte-identical while the UI keeps its exact behaviour:
  - `$writeAudit`: the API writes the ticket_audit trail server-side; the UI
    writes it CLIENT-SIDE (JS -> log_ticket_audit.php), so its adapters pass
    false for update, and true for delete/restore (which the UI DOES audit
    server-side). The shared rules (SQL, closed_datetime, owner sync, template
    emails, CSAT, workflow) run either way.
  - createTicket(`$defaultAnalystId`, `$creationNote`): the UI auto-assigns to the
    creator and audits "Manual ticket created by <name>"; the API leaves it
    unassigned and audits "Created via API (key: X)".
  - deleteTimeEntry(`$restrictToOwner`): the UI lets an analyst delete only their
    own entry; the API (key-authed) may delete any entry on an accessible
    ticket.

Canonical behaviour = the API resource's: unknown status/priority/lookup/analyst
is a 422, empty note text is a 422, a bad date is a 400, and the update is
refused on a trashed ticket (409). The UI converges to these.

## Classes

- [TicketsService](/code/classes/TicketsService.md) (class, line 46)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 118 | `TicketsService::createTicket` | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 123 | `TicketsService::createTicket` | read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 166 | `TicketsService::createTicket` | read [analysts](/code/tables/analysts.md) |  |
| 173 | `TicketsService::createTicket` | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 193 | `TicketsService::createTicket` | read [users](/code/tables/users.md) |  |
| 205 | `TicketsService::createTicket` | read [users](/code/tables/users.md) |  |
| 223 | `TicketsService::createTicket` | **insert** [users](/code/tables/users.md) (`email`, `display_name`, `tenant_id`, `created_at`) |  |
| 237 | `TicketsService::createTicket` | **insert** [tickets](/code/tables/tickets.md) (`tenant_id`, `ticket_number`, `subject`, `status_id`, `priority_id`, `department_id`, `ticket_type_id`, `category_id`, `origin_id`, `assigned_team_id`, `assigned_analyst_id`, `owner_id`) |  |
| 253 | `TicketsService::createTicket` | **insert** [emails](/code/tables/emails.md) (`mailbox_id`, `subject`, `from_address`, `from_name`, `to_recipients`, `received_datetime`, `body_preview`, `body_content`, `body_type`, `has_attachments`, `importance`, `is_read`) |  |
| 471 | `TicketsService::updateTicket` | read [teams](/code/tables/teams.md) |  |
| 499 | `TicketsService::updateTicket` | read [analysts](/code/tables/analysts.md) |  |
| 648 | `TicketsService::updateTicket` | **update** [tickets](/code/tables/tickets.md) |  |
| 740 | `TicketsService::updateTicket` | read [tickets](/code/tables/tickets.md), read [users](/code/tables/users.md) |  |
| 820 | `TicketsService::deleteTicket` | **update** [tickets](/code/tables/tickets.md) (`deleted_datetime`, `deleted_by`) |  |
| 842 | `TicketsService::restoreTicket` | **update** [tickets](/code/tables/tickets.md) (`deleted_datetime`, `deleted_by`) |  |
| 904 | `TicketsService::createNote` | **insert** [ticket_notes](/code/tables/ticket_notes.md) (`ticket_id`, `analyst_id`, `note_text`, `is_internal`, `created_datetime`) |  |
| 907 | `TicketsService::createNote` | **update** [tickets](/code/tables/tickets.md) (`updated_datetime`) |  |
| 982 | `TicketsService::createTimeEntry` | **insert** [ticket_time_entries](/code/tables/ticket_time_entries.md) (`ticket_id`, `analyst_id`, `notes`, `time_spent_minutes`, `entry_datetime`) |  |
| 1006 | `TicketsService::updateTimeEntry` | read [ticket_time_entries](/code/tables/ticket_time_entries.md) |  |
| 1015 | `TicketsService::updateTimeEntry` | **update** [ticket_time_entries](/code/tables/ticket_time_entries.md) (`notes`, `time_spent_minutes`, `entry_datetime`) |  |
| 1031 | `TicketsService::deleteTimeEntry` | read [ticket_time_entries](/code/tables/ticket_time_entries.md) |  |
| 1038 | `TicketsService::deleteTimeEntry` | read [ticket_time_entries](/code/tables/ticket_time_entries.md) |  |
| 1051 | `TicketsService::deleteTimeEntry` | **update** [ticket_time_entries](/code/tables/ticket_time_entries.md) (`is_active`) |  |
| 1100 | `TicketsService::loadTicket` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md), read [ticket_types](/code/tables/ticket_types.md), read [ticket_origins](/code/tables/ticket_origins.md), read [departments](/code/tables/departments.md), read [analysts](/code/tables/analysts.md), read [tenants](/code/tables/tenants.md) |  |
| 1138 | `TicketsService::canAccessTicket` | read [tickets](/code/tables/tickets.md) |  |
| 1161 | `TicketsService::auditWrite` | **insert** [ticket_audit](/code/tables/ticket_audit.md) (`ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |
| 1186 | `TicketsService::resolveStatus` | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 1189 | `TicketsService::resolveStatus` | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 1212 | `TicketsService::resolvePriority` | read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 1215 | `TicketsService::resolvePriority` | read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 1244 | `TicketsService::teamName` | read [teams](/code/tables/teams.md) |  |
| 1264 | `TicketsService::categoryEffectiveTypeId` | read [ticket_categories](/code/tables/ticket_categories.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/ticket_numbering.php](/code/files/includes/ticket_numbering.php.md), [includes/ticket_categories.php](/code/files/includes/ticket_categories.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md), [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md), [includes/services/checklists.php](/code/files/includes/services/checklists.php.md), [includes/services/mandatory_fields.php](/code/files/includes/services/mandatory_fields.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md), [includes/ticket_events.php](/code/files/includes/ticket_events.php.md), [includes/template_email.php](/code/files/includes/template_email.php.md), [includes/csat.php](/code/files/includes/csat.php.md)

**Included by:** [api/calendar/graph_notify.php](/code/files/api/calendar/graph_notify.php.md), [api/self-service/close_ticket.php](/code/files/api/self-service/close_ticket.php.md), [api/tickets/assign_ticket.php](/code/files/api/tickets/assign_ticket.php.md), [api/tickets/bulk_delete_tickets.php](/code/files/api/tickets/bulk_delete_tickets.php.md), [api/tickets/bulk_update_tickets.php](/code/files/api/tickets/bulk_update_tickets.php.md), [api/tickets/create_ticket.php](/code/files/api/tickets/create_ticket.php.md), [api/tickets/delete_ticket.php](/code/files/api/tickets/delete_ticket.php.md), [api/tickets/delete_time_entry.php](/code/files/api/tickets/delete_time_entry.php.md), [api/tickets/get_mandatory_fields_check.php](/code/files/api/tickets/get_mandatory_fields_check.php.md), [api/tickets/get_scheduled_tickets.php](/code/files/api/tickets/get_scheduled_tickets.php.md), [api/tickets/restore_ticket.php](/code/files/api/tickets/restore_ticket.php.md), [api/tickets/save_note.php](/code/files/api/tickets/save_note.php.md), [api/tickets/save_time_entry.php](/code/files/api/tickets/save_time_entry.php.md), [api/tickets/schedule_feed.php](/code/files/api/tickets/schedule_feed.php.md), [api/tickets/schedule_ticket.php](/code/files/api/tickets/schedule_ticket.php.md), [api/tickets/update_ticket_owner.php](/code/files/api/tickets/update_ticket_owner.php.md), [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md), [cron/calendar_sync_pull.php](/code/files/cron/calendar_sync_pull.php.md), [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md), [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md), [includes/ticket_split.php](/code/files/includes/ticket_split.php.md)

**Calls:** [calendarSyncReconcileTicket()](/code/functions/calendarSyncReconcileTicket.md), [csatGetSetting()](/code/functions/csatGetSetting.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [getTenantById()](/code/functions/getTenantById.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [resolveTenantForNewUser()](/code/functions/resolveTenantForNewUser.md), [sendCsatSurvey()](/code/functions/sendCsatSurvey.md), [sendTemplateEmail()](/code/functions/sendTemplateEmail.md), [ticketCategoryPathLabel()](/code/functions/ticketCategoryPathLabel.md), [ticketDispatchCreated()](/code/functions/ticketDispatchCreated.md), [ticketResolutionCodeName()](/code/functions/ticketResolutionCodeName.md), [ticketSensitivityApplyDefaults()](/code/functions/ticketSensitivityApplyDefaults.md), [ticketSensitivityNormalise()](/code/functions/ticketSensitivityNormalise.md), [ticketSensitivityRaise()](/code/functions/ticketSensitivityRaise.md), [ticketSensitivityReady()](/code/functions/ticketSensitivityReady.md)


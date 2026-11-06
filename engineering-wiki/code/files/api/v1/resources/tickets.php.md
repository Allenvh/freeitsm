# api/v1/resources/tickets.php

> API endpoint - 510 lines

FreeITSM REST API v1 — tickets resource.

Mirrors the behaviour of the internal ticket endpoints so a ticket touched
via the API is indistinguishable from one touched in the UI:
  - create mirrors api/tickets/create_ticket.php (initial email row, audit
    row, workflow ticket.created)
  - update mirrors api/tickets/assign_ticket.php (closed_datetime handling,
    owner sync, template emails, CSAT auto-trigger, workflow events) and
    additionally writes the ticket_audit rows the UI writes from JS —
    status audit rows use the status NAME, which the SLA engine parses.
  - delete/restore mirror the trash endpoints (soft delete).

All writes are attributed to the analyst the API key acts as.

The WRITES (create / update / delete / restore / notes / time entries) are
delegated to TicketsService — the same rules the UI's api/tickets/*.php
endpoints now call, gated by ctx->companyScope. The API passes writeAudit=true
so its server-side audit trail is byte-identical; the UI update endpoints pass
writeAudit=false (they audit client-side). The reads, serialisers, list filter
and SLA/thread views below are API-only and stay here. (TicketsService
requires the workflow engine for the ticket.* events.)

## The endpoint

| | |
|---|---|
| URL | `api/v1/resources/tickets.php` |
| Protected by | REST API key |
| Reads | `GET deleted`, `GET state`, `GET status`, `GET priority`, `GET requester_email`, `GET unassigned`, `GET q`, `GET company_id`, `GET sort` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [apiTicketSelect()](/code/functions/apiTicketSelect.md) | 37 | The SELECT for tickets, with status, priority, type, origin, department, analyst, requester and company names. |
| [apiSerializeTicket()](/code/functions/apiSerializeTicket.md) | 64 | Shapes a ticket row for the API - number, subject, status (with is_closed), priority, type, origin, department, analyst, requester and the rest of the record. |
| [apiLoadTicket()](/code/functions/apiLoadTicket.md) | 109 | Load one ticket (with joins) enforcing the key's company scope; 404s if not visible. |
| [apiTicketsList()](/code/functions/apiTicketsList.md) | 130 | GET /tickets |
| [apiTicketsGet()](/code/functions/apiTicketsGet.md) | 252 | GET /tickets/{id} |
| [apiTicketsCreate()](/code/functions/apiTicketsCreate.md) | 270 | POST /tickets |
| [apiTicketsUpdate()](/code/functions/apiTicketsUpdate.md) | 304 | PATCH /tickets/{id} |
| [apiTicketsDelete()](/code/functions/apiTicketsDelete.md) | 314 | DELETE /tickets/{id} + POST /tickets/{id}/restore (soft delete / trash) |
| [apiTicketsRestore()](/code/functions/apiTicketsRestore.md) | 322 | POST /tickets/{id}/restore - restores a deleted ticket through TicketsService and returns it. |
| [apiTicketNotesList()](/code/functions/apiTicketNotesList.md) | 332 | Notes |
| [apiTicketNotesCreate()](/code/functions/apiTicketNotesCreate.md) | 356 | POST /tickets/{id}/notes - adds a note to the ticket through TicketsService. Notes are internal unless is_internal is false. |
| [apiTicketThreadList()](/code/functions/apiTicketThreadList.md) | 371 | Thread (emails / channel messages) |
| [apiTicketAuditList()](/code/functions/apiTicketAuditList.md) | 400 | Audit log |
| [apiTicketSlaGet()](/code/functions/apiTicketSlaGet.md) | 425 | SLA (compute-on-read via includes/sla.php) |
| [apiRequireTimeTracking()](/code/functions/apiRequireTimeTracking.md) | 447 | Refuse if this ticket's company has switched time tracking off FOR THE API (discussion #72). |
| [apiTicketTimeEntriesList()](/code/functions/apiTicketTimeEntriesList.md) | 458 | GET /tickets/{id}/time-entries - the ticket's active time entries, oldest first. Refused when time tracking is off for the ticket. |
| [apiTicketTimeEntriesCreate()](/code/functions/apiTicketTimeEntriesCreate.md) | 485 | POST /tickets/{id}/time-entries - logs time on the ticket through TicketsService, returning the minutes and time as stored. |
| [apiTicketTimeEntriesDelete()](/code/functions/apiTicketTimeEntriesDelete.md) | 503 | DELETE /tickets/{id}/time-entries/{entryId} - deletes a time entry through TicketsService. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 | `apiTicketSelect` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md), read [ticket_types](/code/tables/ticket_types.md), read [ticket_origins](/code/tables/ticket_origins.md), read [departments](/code/tables/departments.md), read [analysts](/code/tables/analysts.md), read [users](/code/tables/users.md), read [tenants](/code/tables/tenants.md) |  |
| 228 | `apiTicketsList` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md), read [users](/code/tables/users.md) |  |
| 258 | `apiTicketsGet` | read [emails](/code/tables/emails.md) |  |
| 335 | `apiTicketNotesList` | read [ticket_notes](/code/tables/ticket_notes.md), read [analysts](/code/tables/analysts.md) |  |
| 374 | `apiTicketThreadList` | read [emails](/code/tables/emails.md) |  |
| 403 | `apiTicketAuditList` | read [ticket_audit](/code/tables/ticket_audit.md), read [analysts](/code/tables/analysts.md) |  |
| 462 | `apiTicketTimeEntriesList` | read [ticket_time_entries](/code/tables/ticket_time_entries.md), read [analysts](/code/tables/analysts.md) |  |
| 491 | `apiTicketTimeEntriesCreate` | read [ticket_time_entries](/code/tables/ticket_time_entries.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md), [includes/sla.php](/code/files/includes/sla.php.md), [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md)

**Included by:** [api/v1/index.php](/code/files/api/v1/index.php.md), [api/workflow/webhook_test.php](/code/files/api/workflow/webhook_test.php.md)

**Calls:** [apiError()](/code/functions/apiError.md), [apiFailFromService()](/code/functions/apiFailFromService.md), [apiIsoDate()](/code/functions/apiIsoDate.md), [apiKeyCanAccessTenant()](/code/functions/apiKeyCanAccessTenant.md), [apiKeyCanAccessTicket()](/code/functions/apiKeyCanAccessTicket.md), [apiKeyDefaultTenantId()](/code/functions/apiKeyDefaultTenantId.md), [apiKeyTicketFilter()](/code/functions/apiKeyTicketFilter.md), [apiPagination()](/code/functions/apiPagination.md), [apiParseDate()](/code/functions/apiParseDate.md), [apiRespond()](/code/functions/apiRespond.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [getTenantById()](/code/functions/getTenantById.md), [sla_get_state()](/code/functions/sla_get_state.md), [ticketTenantId()](/code/functions/ticketTenantId.md), [timeTrackingApiOn()](/code/functions/timeTrackingApiOn.md)


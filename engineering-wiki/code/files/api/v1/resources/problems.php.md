# api/v1/resources/problems.php

> API endpoint - 448 lines

FreeITSM REST API v1 — problems resource (Problem Management).

Mirrors the module's internal endpoints so a problem touched via the API is
indistinguishable from one touched in the UI:
  - create/update mirror api/problem-management/save.php: per-field audit
    rows with the SAME field keys the UI writes ('title', 'status',
    'assigned_to', 'known_error' as Yes/No, …), display names for lookups,
    values truncated to 1000 chars, closed_datetime transitions driven by
    problem_statuses.is_closed, and PRB-##### numbers stamped post-insert.
  - incident linking mirrors link_ticket.php, including the SAME-COMPANY
    rule between problem and ticket; change linking mirrors link_change.php
    via the shared change_relations table (related_type='problem').
  - notes are an append-only journal (no edit/delete — same as the UI).
  - unlinking writes no audit row (parity with the UI).

Problems ARE company-scoped (problems.tenant_id), so the key's company
scope is enforced: apiKeyTenantFilter on lists, apiKeyCanAccessProblem on
every by-id read/write. Invisible on single-company installs.

The WRITES (create / update / delete / notes / incident + change linking)
are delegated to ProblemsService — the same rules the UI's
api/problem-management/*.php endpoints now call, gated by ctx->companyScope.
The reads, serialisers, list filter, and reference lookups below are API-only
and stay here.

## The endpoint

| | |
|---|---|
| URL | `api/v1/resources/problems.php` |
| Protected by | REST API key |
| Reads | `GET state`, `GET status`, `GET priority`, `GET is_known_error`, `GET q`, `GET company_id`, `GET sort` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [apiProblemSelect()](/code/functions/apiProblemSelect.md) | 40 | The SELECT for problems, with status, priority, analyst, creator and company names and how many tickets are linked. |
| [apiSerializeProblem()](/code/functions/apiSerializeProblem.md) | 62 | Shapes a problem row for the API - number, title, status (with is_closed), priority, analyst, known-error flag, root cause, workaround, company and the rest of the record. |
| [apiLoadProblem()](/code/functions/apiLoadProblem.md) | 91 | Load one problem (with joins) enforcing the key's company scope; 404 if not visible. |
| [apiProblemsList()](/code/functions/apiProblemsList.md) | 112 | GET /problems |
| [apiProblemsGet()](/code/functions/apiProblemsGet.md) | 213 | GET /problems/{id} — full detail incl. linked incidents + changes |
| [apiProblemsCreate()](/code/functions/apiProblemsCreate.md) | 265 | POST /problems |
| [apiProblemsUpdate()](/code/functions/apiProblemsUpdate.md) | 295 | PATCH /problems/{id} |
| [apiProblemsDelete()](/code/functions/apiProblemsDelete.md) | 306 | DELETE /problems/{id} — permanent, mirrors delete.php (cascades children, tidies change_relations which has no FK back to problems) |
| [apiProblemNotesList()](/code/functions/apiProblemNotesList.md) | 316 | Notes — append-only journal |
| [apiProblemNotesCreate()](/code/functions/apiProblemNotesCreate.md) | 335 | POST /problems/{id}/notes - adds a note to the problem through ProblemsService. |
| [apiProblemAuditList()](/code/functions/apiProblemAuditList.md) | 349 | GET /problems/{id}/audit |
| [apiProblemTicketsLink()](/code/functions/apiProblemTicketsLink.md) | 374 | Incident linking — POST /problems/{id}/tickets, DELETE .../{ticket_id} |
| [apiProblemTicketsUnlink()](/code/functions/apiProblemTicketsUnlink.md) | 385 | DELETE /problems/{id}/tickets/{ticketId} - unlinks a ticket from the problem through ProblemsService. |
| [apiProblemChangesLink()](/code/functions/apiProblemChangesLink.md) | 396 | Change linking — POST /problems/{id}/changes, DELETE .../{change_id} (shared change_relations table, related_type='problem', relation 'fixes') |
| [apiProblemChangesUnlink()](/code/functions/apiProblemChangesUnlink.md) | 407 | DELETE /problems/{id}/changes/{changeId} - unlinks a change from the problem through ProblemsService. |
| [apiProblemStatusesList()](/code/functions/apiProblemStatusesList.md) | 417 | Reference lookups |
| [apiProblemPrioritiesList()](/code/functions/apiProblemPrioritiesList.md) | 434 | GET /problem-priorities - the problem priorities, in display order. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 41 | `apiProblemSelect` | read [problem_tickets](/code/tables/problem_tickets.md), read [problems](/code/tables/problems.md), read [problem_statuses](/code/tables/problem_statuses.md), read [problem_priorities](/code/tables/problem_priorities.md), read [analysts](/code/tables/analysts.md), read [tenants](/code/tables/tenants.md) |  |
| 190 | `apiProblemsList` | read [problems](/code/tables/problems.md), read [problem_statuses](/code/tables/problem_statuses.md), read [problem_priorities](/code/tables/problem_priorities.md) |  |
| 218 | `apiProblemsGet` | read [problem_tickets](/code/tables/problem_tickets.md), read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 241 | `apiProblemsGet` | read [change_relations](/code/tables/change_relations.md), read [changes](/code/tables/changes.md), read [change_statuses](/code/tables/change_statuses.md) |  |
| 319 | `apiProblemNotesList` | read [problem_notes](/code/tables/problem_notes.md), read [analysts](/code/tables/analysts.md) |  |
| 352 | `apiProblemAuditList` | read [problem_audit](/code/tables/problem_audit.md), read [analysts](/code/tables/analysts.md) |  |
| 419 | `apiProblemStatusesList` | read [problem_statuses](/code/tables/problem_statuses.md) |  |
| 436 | `apiProblemPrioritiesList` | read [problem_priorities](/code/tables/problem_priorities.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/services/problems.php](/code/files/includes/services/problems.php.md)

**Included by:** [api/v1/index.php](/code/files/api/v1/index.php.md)

**Calls:** [apiError()](/code/functions/apiError.md), [apiFailFromService()](/code/functions/apiFailFromService.md), [apiIsoDate()](/code/functions/apiIsoDate.md), [apiKeyCanAccessProblem()](/code/functions/apiKeyCanAccessProblem.md), [apiKeyCanAccessTenant()](/code/functions/apiKeyCanAccessTenant.md), [apiKeyDefaultTenantId()](/code/functions/apiKeyDefaultTenantId.md), [apiKeyTenantFilter()](/code/functions/apiKeyTenantFilter.md), [apiPagination()](/code/functions/apiPagination.md), [apiParseDate()](/code/functions/apiParseDate.md), [apiRespond()](/code/functions/apiRespond.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [getTenantById()](/code/functions/getTenantById.md)


# includes/services/problems.php

> PHP - 568 lines

ProblemsService — the shared write rules for Problem Management: create/update a problem (with the per-field audit trail + PRB-##### numbering), delete, the append-only notes journal, and incident/change linking with the same-company rule.

Shared by the UI endpoints (api/problem-management/*.php) and the REST API
(api/v1/resources/problems.php).

TENANCY — this is the first tenant-scoped module on the service layer. Every
by-id read/write is gated by the actor's company scope via ctx->companyScope
(null = all companies, else the accessible tenant ids). The generic
`canAccessTenantRow()` mirrors `apiKeyCanAccessTenantRow()` / `analystCanAccessX()`
exactly (NULL tenant_id normalises to the Default company), so a problem or
ticket outside the caller's scope is a 404 for BOTH transports. The "acting
company" for a *create* is resolved by the adapter (the API's explicit
company_id / key default, the UI's active tenant) and passed in as `$tenantId` —
it is auth-adjacent, like actorId.

Canonical behaviour = the API resource's, so the API stays byte-identical
while the UI's looser writes converge: unknown status/priority/analyst → 422,
empty text → null, a duplicate link → 409, and an unlink / edit of something
gone → 404 (the UI silently succeeded).

## Classes

- [ProblemsService](/code/classes/ProblemsService.md) (class, line 31)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 55 | `ProblemsService::createProblem` | read [problem_statuses](/code/tables/problem_statuses.md) |  |
| 73 | `ProblemsService::createProblem` | **insert** [problems](/code/tables/problems.md) (`tenant_id`, `title`, `description`, `status_id`, `priority_id`, `assigned_analyst_id`, `root_cause`, `workaround`, `is_known_error`, `created_by_id`, `created_datetime`, `updated_datetime`) |  |
| 84 | `ProblemsService::createProblem` | **update** [problems](/code/tables/problems.md) (`problem_number`) |  |
| 210 | `ProblemsService::updateProblem` | **update** [problems](/code/tables/problems.md) |  |
| 244 | `ProblemsService::deleteProblem` | **delete** [change_relations](/code/tables/change_relations.md) |  |
| 247 | `ProblemsService::deleteProblem` | **delete** [problems](/code/tables/problems.md) |  |
| 268 | `ProblemsService::addNote` | **insert** [problem_notes](/code/tables/problem_notes.md) (`problem_id`, `analyst_id`, `note`, `created_datetime`) |  |
| 297 | `ProblemsService::linkTicket` | read [tickets](/code/tables/tickets.md) |  |
| 314 | `ProblemsService::linkTicket` | read [problem_tickets](/code/tables/problem_tickets.md) |  |
| 320 | `ProblemsService::linkTicket` | **insert** [problem_tickets](/code/tables/problem_tickets.md) (`problem_id`, `ticket_id`, `created_by_id`, `created_datetime`) |  |
| 336 | `ProblemsService::unlinkTicket` | **delete** [problem_tickets](/code/tables/problem_tickets.md) |  |
| 365 | `ProblemsService::linkChange` | read [changes](/code/tables/changes.md) |  |
| 375 | `ProblemsService::linkChange` | read [change_relations](/code/tables/change_relations.md) |  |
| 382 | `ProblemsService::linkChange` | **insert** [change_relations](/code/tables/change_relations.md) (`change_id`, `related_type`, `related_id`, `relation_type`, `created_by_id`, `created_datetime`) |  |
| 401 | `ProblemsService::unlinkChange` | **delete** [change_relations](/code/tables/change_relations.md) |  |
| 426 | `ProblemsService::loadProblem` | read [problems](/code/tables/problems.md), read [problem_statuses](/code/tables/problem_statuses.md), read [problem_priorities](/code/tables/problem_priorities.md), read [analysts](/code/tables/analysts.md) |  |
| 488 | `ProblemsService::auditWrite` | **insert** [problem_audit](/code/tables/problem_audit.md) (`problem_id`, `analyst_id`, `action_type`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |
| 504 | `ProblemsService::touch` | **update** [problems](/code/tables/problems.md) (`updated_datetime`) |  |
| 515 | `ProblemsService::resolveStatus` | read [problem_statuses](/code/tables/problem_statuses.md) |  |
| 518 | `ProblemsService::resolveStatus` | read [problem_statuses](/code/tables/problem_statuses.md) |  |
| 541 | `ProblemsService::resolvePriority` | read [problem_priorities](/code/tables/problem_priorities.md) |  |
| 544 | `ProblemsService::resolvePriority` | read [problem_priorities](/code/tables/problem_priorities.md) |  |
| 559 | `ProblemsService::resolveAnalyst` | read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md)

**Included by:** [api/problem-management/add_note.php](/code/files/api/problem-management/add_note.php.md), [api/problem-management/delete.php](/code/files/api/problem-management/delete.php.md), [api/problem-management/link_change.php](/code/files/api/problem-management/link_change.php.md), [api/problem-management/link_ticket.php](/code/files/api/problem-management/link_ticket.php.md), [api/problem-management/save.php](/code/files/api/problem-management/save.php.md), [api/problem-management/unlink_change.php](/code/files/api/problem-management/unlink_change.php.md), [api/problem-management/unlink_ticket.php](/code/files/api/problem-management/unlink_ticket.php.md), [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md)

**Calls:** [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)


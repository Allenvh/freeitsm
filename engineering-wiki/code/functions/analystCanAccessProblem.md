# analystCanAccessProblem()

May this analyst access this *problem* (by its owning company)? The Problem Management twin of `analystCanAccessTicket()` — same rules (single-company → always true; NULL tenant treated as Default-owned; unknown id → false; part-migrated table → true).

```php
function analystCanAccessProblem(PDO $conn, int $analystId, $problemId): bool
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 337.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$problemId` |  |  | the problem (problems.id), any form; cast to int, and 0 or less is false |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 346 | `analystCanAccessProblem` | read [problems](/code/tables/problems.md) |  |

## Calls

[analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [tenancyDegradeAllowed()](/code/functions/tenancyDegradeAllowed.md)

## Called from

- [api/problem-management/ai_root_cause.php](/code/files/api/problem-management/ai_root_cause.php.md)
- [api/problem-management/get.php](/code/files/api/problem-management/get.php.md)
- [api/problem-management/list_linkable_changes.php](/code/files/api/problem-management/list_linkable_changes.php.md)
- [api/problem-management/list_linkable_tickets.php](/code/files/api/problem-management/list_linkable_tickets.php.md)
- [includes/documents.php](/code/files/includes/documents.php.md) - in [documentEntityRegistry()](/code/functions/documentEntityRegistry.md)
- [includes/projects/links.php](/code/files/includes/projects/links.php.md) - in [projectLinkTargetOk()](/code/functions/projectLinkTargetOk.md)
- [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) - in [recentTrailLabelsForType()](/code/functions/recentTrailLabelsForType.md)
- [includes/record_preview.php](/code/files/includes/record_preview.php.md) - in [recordPreviewProblem()](/code/functions/recordPreviewProblem.md)


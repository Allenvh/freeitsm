# analystCanAccessChange()

May this analyst access this *change* (by its owning company)? The Change Management twin of `analystCanAccessProblem()` — same rules (single-company → always true; NULL tenant treated as Default-owned; unknown id → false; part-migrated table → true).

```php
function analystCanAccessChange(PDO $conn, int $analystId, $changeId): bool
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 367.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$changeId` |  |  | the change (changes.id), any form; cast to int, and 0 or less is false |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 376 | `analystCanAccessChange` | read [changes](/code/tables/changes.md) |  |

## Calls

[analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [tenancyDegradeAllowed()](/code/functions/tenancyDegradeAllowed.md)

## Called from

- [api/change-management/delete_attachment.php](/code/files/api/change-management/delete_attachment.php.md)
- [api/change-management/get.php](/code/files/api/change-management/get.php.md)
- [api/change-management/get_attachment.php](/code/files/api/change-management/get_attachment.php.md)
- [api/change-management/get_audit.php](/code/files/api/change-management/get_audit.php.md)
- [api/change-management/get_comments.php](/code/files/api/change-management/get_comments.php.md)
- [api/change-management/list_linkable_tickets.php](/code/files/api/change-management/list_linkable_tickets.php.md)
- [api/change-management/move_to_company.php](/code/files/api/change-management/move_to_company.php.md)
- [api/change-management/upload_attachment.php](/code/files/api/change-management/upload_attachment.php.md)
- [includes/documents.php](/code/files/includes/documents.php.md) - in [documentEntityRegistry()](/code/functions/documentEntityRegistry.md)
- [includes/projects/links.php](/code/files/includes/projects/links.php.md) - in [projectLinkTargetOk()](/code/functions/projectLinkTargetOk.md)
- [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) - in [recentTrailLabelsForType()](/code/functions/recentTrailLabelsForType.md)
- [includes/record_preview.php](/code/files/includes/record_preview.php.md) - in [recordPreviewChange()](/code/functions/recordPreviewChange.md)


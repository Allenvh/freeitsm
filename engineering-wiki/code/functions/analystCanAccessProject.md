# analystCanAccessProject()

May this analyst access this *project* (by its company)? The same rules as a change: single-company -> yes; NULL company = the Default one; unknown id -> no. Added for documents on projects (3.3.0).

```php
function analystCanAccessProject(PDO $conn, int $analystId, $projectId): bool
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 396.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` |  |  | the project id, any form; cast to int, and 0 or less is false |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 404 | `analystCanAccessProject` | read [projects](/code/tables/projects.md) |  |

## Calls

[analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [projectIdVisibleTo()](/code/functions/projectIdVisibleTo.md), [tenancyDegradeAllowed()](/code/functions/tenancyDegradeAllowed.md)

## Called from

- [includes/documents.php](/code/files/includes/documents.php.md) - in [documentEntityRegistry()](/code/functions/documentEntityRegistry.md)


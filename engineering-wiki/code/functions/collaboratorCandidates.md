# collaboratorCandidates()

Who may be offered as a collaborator on this task: active analysts, minus the owner, minus anybody already on it, and — on a multi-company install — only those who can reach the task's own company.

```php
function collaboratorCandidates(PDO $conn, array $task): array
```

Defined in [api/tasks/collaborators.php](/code/files/api/tasks/collaborators.php.md), line 93.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$task` | `array` |  | the task row from TasksService::taskForCollaborators() - id, assigned_analyst_id, tenant_id |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 95 | `collaboratorCandidates` | read [analysts](/code/tables/analysts.md) |  |

## Calls

[analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [api/tasks/collaborators.php](/code/files/api/tasks/collaborators.php.md)


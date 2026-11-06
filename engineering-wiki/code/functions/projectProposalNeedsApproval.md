# projectProposalNeedsApproval()

Does a new project wait for approval? `$fromForm` = it was proposed through a form.

```php
function projectProposalNeedsApproval(PDO $conn, bool $fromForm): bool
```

Defined in [includes/projects/intake.php](/code/files/includes/projects/intake.php.md), line 49.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$fromForm` | `bool` |  | true when the project was proposed through a form |

**Returns:** `bool`

## Calls

[projectIntakeReady()](/code/functions/projectIntakeReady.md), [projectSetting()](/code/functions/projectSetting.md)

## Called from

- [includes/services/projects.php](/code/files/includes/services/projects.php.md) - in `ProjectsService::createProject`


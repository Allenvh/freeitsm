# projectIntakeReady()

Has Database Verification added the columns? Everything here is quiet before it.

```php
function projectIntakeReady(PDO $conn): bool
```

Defined in [includes/projects/intake.php](/code/files/includes/projects/intake.php.md), line 34.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 | `projectIntakeReady` | read [projects](/code/tables/projects.md) |  |

## Called from

- [includes/projects/intake.php](/code/files/includes/projects/intake.php.md) - in [projectProposalNeedsApproval()](/code/functions/projectProposalNeedsApproval.md), [projectProposalDetail()](/code/functions/projectProposalDetail.md), [projectProposalApprovalColumn()](/code/functions/projectProposalApprovalColumn.md)
- [includes/projects/nudges.php](/code/files/includes/projects/nudges.php.md) - in [projectAlertsStalled()](/code/functions/projectAlertsStalled.md)
- [includes/services/projects.php](/code/files/includes/services/projects.php.md) - in `ProjectsService::createProject`


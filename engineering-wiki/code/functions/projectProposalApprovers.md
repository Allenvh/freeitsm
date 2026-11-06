# projectProposalApprovers()

Who is told a proposal is waiting: the named approver if there is one, otherwise everybody who holds Manage Projects (admins included).

```php
function projectProposalApprovers(PDO $conn): array
```

Defined in [includes/projects/intake.php](/code/files/includes/projects/intake.php.md), line 79.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 83 | `projectProposalApprovers` | read [analysts](/code/tables/analysts.md) |  |

## Calls

[projectIsManager()](/code/functions/projectIsManager.md), [projectProposalNamedApprover()](/code/functions/projectProposalNamedApprover.md)

## Called from

- [includes/projects/nudges.php](/code/files/includes/projects/nudges.php.md) - in [projectAlertsStalled()](/code/functions/projectAlertsStalled.md)
- [includes/services/projects.php](/code/files/includes/services/projects.php.md) - in `ProjectsService::proposalEvent`


# projectCanDecideProposal()

May this analyst approve or reject proposals? The named approver, and Manage Projects always.

```php
function projectCanDecideProposal(PDO $conn, int $analystId): bool
```

Defined in [includes/projects/intake.php](/code/files/includes/projects/intake.php.md), line 68.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Calls

[projectIsManager()](/code/functions/projectIsManager.md), [projectProposalNamedApprover()](/code/functions/projectProposalNamedApprover.md)

## Called from

- [includes/projects/intake.php](/code/files/includes/projects/intake.php.md) - in [projectProposalDetail()](/code/functions/projectProposalDetail.md)
- [includes/services/projects.php](/code/files/includes/services/projects.php.md) - in `ProjectsService::decideProposal`


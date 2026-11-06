# projectIsManager()

Holds Manage Projects (admins always do - analystHasCapability short-circuits is_admin).

```php
function projectIsManager(PDO $conn, int $analystId): bool
```

Defined in [includes/projects/settings.php](/code/files/includes/projects/settings.php.md), line 419.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Calls

[analystHasCapability()](/code/functions/analystHasCapability.md)

## Called from

- [includes/projects/control.php](/code/files/includes/projects/control.php.md) - in [projectCanDecideChange()](/code/functions/projectCanDecideChange.md)
- [includes/projects/intake.php](/code/files/includes/projects/intake.php.md) - in [projectCanDecideProposal()](/code/functions/projectCanDecideProposal.md), [projectProposalApprovers()](/code/functions/projectProposalApprovers.md)
- [includes/projects/settings.php](/code/files/includes/projects/settings.php.md) - in [projectCanCreate()](/code/functions/projectCanCreate.md), [projectCanChange()](/code/functions/projectCanChange.md), [projectCanDelete()](/code/functions/projectCanDelete.md)
- [includes/projects/visibility.php](/code/files/includes/projects/visibility.php.md) - in [projectSeesAllPrivate()](/code/functions/projectSeesAllPrivate.md)
- [includes/services/projects.php](/code/files/includes/services/projects.php.md) - in `ProjectsService::updateProject`


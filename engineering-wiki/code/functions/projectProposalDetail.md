# projectProposalDetail()

The proposal panel on a project's page; null when it never needed approval.

```php
function projectProposalDetail(PDO $conn, array $project, int $analystId): ?array
```

Defined in [includes/projects/intake.php](/code/files/includes/projects/intake.php.md), line 105.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$project` | `array` |  | the projects row - only its id is read |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 108 | `projectProposalDetail` | read [projects](/code/tables/projects.md), read [analysts](/code/tables/analysts.md), read [form_submissions](/code/tables/form_submissions.md), read [forms](/code/tables/forms.md) |  |

## Calls

[projectCanDecideProposal()](/code/functions/projectCanDecideProposal.md), [projectIntakeReady()](/code/functions/projectIntakeReady.md), [projectSetting()](/code/functions/projectSetting.md)

## Called from

- [api/projects/get.php](/code/files/api/projects/get.php.md)


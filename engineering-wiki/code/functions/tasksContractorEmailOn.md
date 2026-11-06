# tasksContractorEmailOn()

Is project_contractor_email on?

```php
function tasksContractorEmailOn(PDO $conn): bool
```

Defined in [includes/task_contractors.php](/code/files/includes/task_contractors.php.md), line 130.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Calls

[projectSetting()](/code/functions/projectSetting.md)

## Called from

- [includes/task_contractors.php](/code/files/includes/task_contractors.php.md) - in [tasksContractorEmail()](/code/functions/tasksContractorEmail.md), [tasksContractorReminders()](/code/functions/tasksContractorReminders.md)


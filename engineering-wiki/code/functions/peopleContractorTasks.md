# peopleContractorTasks()

Tasks given to a supplier, or to one person there, as a contractor (3.3.0, includes/task_contractors.php) - open first. In companies the analyst can access; a task in a members-only project they cannot see is left out.

```php
function peopleContractorTasks(PDO $conn, int $analystId, array $who): array
```

Defined in [includes/people.php](/code/files/includes/people.php.md), line 780.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$who` | `array` |  | ['supplier' => suppliers id] for the whole supplier, or ['contact' => contacts id] for one person there |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 789 | `peopleContractorTasks` | read [projects](/code/tables/projects.md) |  |
| 793 | `peopleContractorTasks` | read [projects](/code/tables/projects.md), read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md), read [analysts](/code/tables/analysts.md), read [contacts](/code/tables/contacts.md) |  |

## Calls

[entityLink()](/code/functions/entityLink.md), [peopleScope()](/code/functions/peopleScope.md), [projectVisibleSql()](/code/functions/projectVisibleSql.md), [tasksContractorReady()](/code/functions/tasksContractorReady.md)

## Called from

- [includes/people.php](/code/files/includes/people.php.md) - in [supplierDetail()](/code/functions/supplierDetail.md), [supplierContactDetail()](/code/functions/supplierContactDetail.md)


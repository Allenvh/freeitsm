# tasksContractorEmail()

Email a task's contractor contact - `$kind` 'assigned', 'due' (due soon) or 'overdue'. Quiet: false when the setting is off, there is no contact email or the send fails (the email log says which). Never throws.

```php
function tasksContractorEmail(PDO $conn, int $taskId, string $kind): bool
```

Defined in [includes/task_contractors.php](/code/files/includes/task_contractors.php.md), line 147.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$taskId` | `int` |  | The id of the task. *([standard](/handbook/standard-parameters.md))* |
| `$kind` | `string` |  | 'assigned', 'due' (due soon) or 'overdue' |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 152 | `tasksContractorEmail` | read [tasks](/code/tables/tasks.md), read [contacts](/code/tables/contacts.md), read [suppliers](/code/tables/suppliers.md), read [analysts](/code/tables/analysts.md) |  |
| 164 | `tasksContractorEmail` | read [projects](/code/tables/projects.md) |  |

## Calls

[ssSendSystemEmail()](/code/functions/ssSendSystemEmail.md), [systemName()](/code/functions/systemName.md), [tasksContractorEmailOn()](/code/functions/tasksContractorEmailOn.md), [tasksContractorReady()](/code/functions/tasksContractorReady.md), [tasksSupplierNameSql()](/code/functions/tasksSupplierNameSql.md)

## Called from

- [includes/services/tasks.php](/code/files/includes/services/tasks.php.md) - in `TasksService::createTask`, `TasksService::updateTask`
- [includes/task_contractors.php](/code/files/includes/task_contractors.php.md) - in [tasksContractorReminders()](/code/functions/tasksContractorReminders.md)


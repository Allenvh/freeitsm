# tasksContractorReady()

Has Database Verification added the two columns? Before it, there are no contractors and no errors.

```php
function tasksContractorReady(PDO $conn): bool
```

Defined in [includes/task_contractors.php](/code/files/includes/task_contractors.php.md), line 31.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 35 | `tasksContractorReady` | read [tasks](/code/tables/tasks.md) |  |

## Called from

- [api/tasks/contractors.php](/code/files/api/tasks/contractors.php.md)
- [includes/people.php](/code/files/includes/people.php.md) - in [peopleContractorTasks()](/code/functions/peopleContractorTasks.md)
- [includes/projects/ai.php](/code/files/includes/projects/ai.php.md) - in [projectAiFacts()](/code/functions/projectAiFacts.md)
- [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md) - in [projectChatRead()](/code/functions/projectChatRead.md)
- [includes/projects/capacity.php](/code/files/includes/projects/capacity.php.md) - in [projectCapacity()](/code/functions/projectCapacity.md)
- [includes/projects/nudges.php](/code/files/includes/projects/nudges.php.md) - in [projectAlertsOverdueDigest()](/code/functions/projectAlertsOverdueDigest.md)
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::addContractorMember`
- [includes/services/tasks.php](/code/files/includes/services/tasks.php.md) - in `TasksService::createTask`
- [includes/task_contractors.php](/code/files/includes/task_contractors.php.md) - in [tasksContractors()](/code/functions/tasksContractors.md), [tasksContractorEmail()](/code/functions/tasksContractorEmail.md), [tasksContractorReminders()](/code/functions/tasksContractorReminders.md)


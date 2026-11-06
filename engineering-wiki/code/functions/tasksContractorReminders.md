# tasksContractorReminders()

The reminders, from the hourly scan (projectAlertsScan): two days before a contractor's task is due, and once the day after it is missed - each once per due date (the ledger), so moving the date re-arms them. Open tasks with a contact only; nothing when the setting is off. Returns how many were sent.

```php
function tasksContractorReminders(PDO $conn): int
```

Defined in [includes/task_contractors.php](/code/files/includes/task_contractors.php.md), line 204.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 207 | `tasksContractorReminders` | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md) |  |
| 213 | `tasksContractorReminders` | **insert** [workflow_scheduled_emissions](/code/tables/workflow_scheduled_emissions.md) (`trigger_event`, `entity_key`, `fingerprint`, `emitted_datetime`) |  |

## Calls

[tasksContractorEmail()](/code/functions/tasksContractorEmail.md), [tasksContractorEmailOn()](/code/functions/tasksContractorEmailOn.md), [tasksContractorReady()](/code/functions/tasksContractorReady.md)

## Called from

- [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md) - in [projectAlertsScan()](/code/functions/projectAlertsScan.md)


# projectCapacity()

The capacity view: each analyst's project hours and rota desk hours, week by week for the next `$weeks` weeks, plus open tickets and work given to contractors.

```php
function projectCapacity(PDO $conn, int $viewerId, int $weeks = 4): array
```

Defined in [includes/projects/capacity.php](/code/files/includes/projects/capacity.php.md), line 83.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$viewerId` | `int` |  | the analyst looking - which companies, projects and modules they may see |
| `$weeks` | `int` | `4` | 1-12 |

**Returns:** array{weeks:array, hours_per_week:float, amber:int, several:int, can_tickets:bool, rows:array, team_hours:float, team_tasks:int}

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 113 | `projectCapacity` | read [projects](/code/tables/projects.md) |  |
| 122 | `projectCapacity` | read [projects](/code/tables/projects.md) |  |
| 136 | `projectCapacity` | read [task_time_entries](/code/tables/task_time_entries.md), read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md) |  |
| 140 | `projectCapacity` | read [suppliers](/code/tables/suppliers.md) |  |
| 155 | `projectCapacity` | read [project_members](/code/tables/project_members.md) |  |
| 213 | `projectCapacity` | read [analysts](/code/tables/analysts.md) |  |
| 221 | `projectCapacity` | read [ticket_rota_entries](/code/tables/ticket_rota_entries.md), read [ticket_rota_shifts](/code/tables/ticket_rota_shifts.md) |  |
| 231 | `projectCapacity` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |

## Calls

[activeTenantReadFilter()](/code/functions/activeTenantReadFilter.md), [allAccessibleTenantsFilter()](/code/functions/allAccessibleTenantsFilter.md), [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [projectCapacityShiftHours()](/code/functions/projectCapacityShiftHours.md), [projectCapacityWorkdays()](/code/functions/projectCapacityWorkdays.md), [projectCode()](/code/functions/projectCode.md), [projectEstimatesReady()](/code/functions/projectEstimatesReady.md), [projectSettings()](/code/functions/projectSettings.md), [projectVisibleSql()](/code/functions/projectVisibleSql.md), [t()](/code/functions/t.md), [tasksContractorReady()](/code/functions/tasksContractorReady.md), [tasksSupplierNameSql()](/code/functions/tasksSupplierNameSql.md)

## Called from

- [api/projects/capacity.php](/code/files/api/projects/capacity.php.md)


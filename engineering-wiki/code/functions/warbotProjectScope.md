# warbotProjectScope()

The asking analyst's projects: their active company, or every company they can see under "All".

```php
function warbotProjectScope(PDO $conn, int $analystId): array
```

Defined in [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md), line 999.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Calls

[activeTenantReadFilter()](/code/functions/activeTenantReadFilter.md), [projectVisibleSql()](/code/functions/projectVisibleSql.md)

## Called from

- [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) - in [warbotToolListProjects()](/code/functions/warbotToolListProjects.md), [warbotToolProjectOverview()](/code/functions/warbotToolProjectOverview.md), [warbotToolProjectRaid()](/code/functions/warbotToolProjectRaid.md), [warbotToolProjectTasks()](/code/functions/warbotToolProjectTasks.md), [warbotToolProjectDates()](/code/functions/warbotToolProjectDates.md)


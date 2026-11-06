# projectChangeStats()

Per project: change requests waiting for a decision - for the Overview, the card and the assistant.

```php
function projectChangeStats(PDO $conn, array $projectIds): array
```

Defined in [includes/projects/control.php](/code/files/includes/projects/control.php.md), line 293.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectIds` | `array` |  | the project ids to count for |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 298 | `projectChangeStats` | read [project_change_requests](/code/tables/project_change_requests.md) |  |

## Calls

[projectControlReady()](/code/functions/projectControlReady.md)

## Called from

- [includes/projects/read.php](/code/files/includes/projects/read.php.md) - in [projectTaskStats()](/code/functions/projectTaskStats.md)


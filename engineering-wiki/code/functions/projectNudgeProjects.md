# projectNudgeProjects()

Project rows shaped for `projectEventPayload()`, by id.

```php
function projectNudgeProjects(PDO $conn, array $ids): array
```

Defined in [includes/projects/nudges.php](/code/files/includes/projects/nudges.php.md), line 172.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ids` | `array` |  | the project ids to load; non-numbers and 0 are dropped |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 178 | `projectNudgeProjects` | read [projects](/code/tables/projects.md), read [analysts](/code/tables/analysts.md) |  |

## Called from

- [includes/projects/nudges.php](/code/files/includes/projects/nudges.php.md) - in [projectAlertsOverdueDigest()](/code/functions/projectAlertsOverdueDigest.md), [projectAlertsStalled()](/code/functions/projectAlertsStalled.md)


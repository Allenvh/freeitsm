# projectBenefitStats()

Per project: benefits open and reviews due - for the Overview, the card and the assistant.

```php
function projectBenefitStats(PDO $conn, array $projectIds): array
```

Defined in [includes/projects/benefits.php](/code/files/includes/projects/benefits.php.md), line 118.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectIds` | `array` |  | the project ids to count for |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 123 | `projectBenefitStats` | read [project_benefits](/code/tables/project_benefits.md) |  |

## Calls

[projectBenefitsReady()](/code/functions/projectBenefitsReady.md)

## Called from

- [includes/projects/read.php](/code/files/includes/projects/read.php.md) - in [projectTaskStats()](/code/functions/projectTaskStats.md)


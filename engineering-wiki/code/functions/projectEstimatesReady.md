# projectEstimatesReady()

Has Database Verification added tasks.estimate_hours (3.3.0)? Reads stay working before it.

```php
function projectEstimatesReady(PDO $conn): bool
```

Defined in [includes/projects/read.php](/code/files/includes/projects/read.php.md), line 358.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 362 | `projectEstimatesReady` | read [tasks](/code/tables/tasks.md) |  |

## Called from

- [includes/projects/ai.php](/code/files/includes/projects/ai.php.md) - in [projectAiFacts()](/code/functions/projectAiFacts.md)
- [includes/projects/capacity.php](/code/files/includes/projects/capacity.php.md) - in [projectCapacity()](/code/functions/projectCapacity.md)
- [includes/projects/read.php](/code/files/includes/projects/read.php.md) - in [projectDetail()](/code/functions/projectDetail.md)
- [includes/projects/templates.php](/code/files/includes/projects/templates.php.md) - in [projectTemplateCapture()](/code/functions/projectTemplateCapture.md)


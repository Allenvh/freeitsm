# projectGateKindReady()

Has Database Verification added project_stages.gate_kind (3.3.0)?

```php
function projectGateKindReady(PDO $conn): bool
```

Defined in [includes/projects/templates.php](/code/files/includes/projects/templates.php.md), line 48.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 52 | `projectGateKindReady` | read [project_stages](/code/tables/project_stages.md) |  |

## Called from

- [includes/projects/templates.php](/code/files/includes/projects/templates.php.md) - in [projectTemplateCapture()](/code/functions/projectTemplateCapture.md)


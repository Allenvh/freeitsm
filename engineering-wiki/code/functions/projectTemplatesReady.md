# projectTemplatesReady()

True when the project_templates table exists (after Database Verification). Cached per request.

```php
function projectTemplatesReady(PDO $conn): bool
```

Defined in [includes/projects/templates.php](/code/files/includes/projects/templates.php.md), line 58.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 62 | `projectTemplatesReady` | read [project_templates](/code/tables/project_templates.md) |  |

## Called from

- [api/projects/templates.php](/code/files/api/projects/templates.php.md)
- [includes/projects/templates.php](/code/files/includes/projects/templates.php.md) - in [projectTemplateList()](/code/functions/projectTemplateList.md), [projectTemplateLoad()](/code/functions/projectTemplateLoad.md)
- [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md) - in `ProjectTemplatesService::saveFromProject`, `ProjectTemplatesService::row`


# projectGateItemsReady()

True when the project_gate_items table exists (after Database Verification). Cached per request.

```php
function projectGateItemsReady(PDO $conn): bool
```

Defined in [includes/projects/gatecheck.php](/code/files/includes/projects/gatecheck.php.md), line 31.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 35 | `projectGateItemsReady` | read [project_gate_items](/code/tables/project_gate_items.md) |  |

## Called from

- [includes/projects/gatecheck.php](/code/files/includes/projects/gatecheck.php.md) - in [projectGateItems()](/code/functions/projectGateItems.md)
- [includes/projects/templates.php](/code/files/includes/projects/templates.php.md) - in [projectTemplateCapture()](/code/functions/projectTemplateCapture.md)
- [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md) - in `ProjectTemplatesService::createFromTemplate`
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::saveGateItem`


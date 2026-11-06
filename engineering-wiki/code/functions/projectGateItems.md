# projectGateItems()

Every gate item of a project, each with `done` worked out, grouped by stage id. `$showChanges` = this analyst may open Changes (otherwise a change's title is left out).

```php
function projectGateItems(PDO $conn, int $projectId, bool $showChanges = true): array
```

Defined in [includes/projects/gatecheck.php](/code/files/includes/projects/gatecheck.php.md), line 69.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$showChanges` | `bool` | `true` | whether the analyst may open Changes; false leaves each change's title out |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 72 | `projectGateItems` | read [project_gate_items](/code/tables/project_gate_items.md), read [analysts](/code/tables/analysts.md) |  |

## Calls

[projectGateChanges()](/code/functions/projectGateChanges.md), [projectGateDocuments()](/code/functions/projectGateDocuments.md), [projectGateItemsReady()](/code/functions/projectGateItemsReady.md)

## Called from

- [api/projects/get.php](/code/files/api/projects/get.php.md)
- [includes/projects/ai.php](/code/files/includes/projects/ai.php.md) - in [projectAiFacts()](/code/functions/projectAiFacts.md)
- [includes/projects/gatecheck.php](/code/files/includes/projects/gatecheck.php.md) - in [projectGateOpenItems()](/code/functions/projectGateOpenItems.md)


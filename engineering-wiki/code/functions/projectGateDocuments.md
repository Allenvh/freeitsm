# projectGateDocuments()

The project's attached documents as {id, title} - what a document item may point at.

```php
function projectGateDocuments(PDO $conn, int $projectId): array
```

Defined in [includes/projects/gatecheck.php](/code/files/includes/projects/gatecheck.php.md), line 42.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 45 | `projectGateDocuments` | read [document_links](/code/tables/document_links.md), read [documents](/code/tables/documents.md) |  |

## Called from

- [api/projects/get.php](/code/files/api/projects/get.php.md)
- [includes/projects/gatecheck.php](/code/files/includes/projects/gatecheck.php.md) - in [projectGateItems()](/code/functions/projectGateItems.md)
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::tickGateItem`


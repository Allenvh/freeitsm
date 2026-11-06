# projectLinkDescribe()

Linked records, shaped for display: id, label, a sub-line, a status and a link.

```php
function projectLinkDescribe(PDO $conn, string $kind, array $ids): array
```

Defined in [includes/projects/links.php](/code/files/includes/projects/links.php.md), line 182.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$kind` | `string` |  | the link kind, e.g. 'contract' |
| `$ids` | `array` |  | the record ids to describe (already checked as visible) |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 189 | `projectLinkDescribe` | read [assets](/code/tables/assets.md), read [asset_status_types](/code/tables/asset_status_types.md) |  |
| 198 | `projectLinkDescribe` | read [changes](/code/tables/changes.md), read [change_statuses](/code/tables/change_statuses.md) |  |
| 207 | `projectLinkDescribe` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 216 | `projectLinkDescribe` | read [contracts](/code/tables/contracts.md), read [contract_statuses](/code/tables/contract_statuses.md), read [suppliers](/code/tables/suppliers.md) |  |
| 227 | `projectLinkDescribe` | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md) |  |
| 233 | `projectLinkDescribe` | read [problems](/code/tables/problems.md), read [problem_statuses](/code/tables/problem_statuses.md) |  |
| 241 | `projectLinkDescribe` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## Calls

[entityLink()](/code/functions/entityLink.md)

## Called from

- [includes/projects/links.php](/code/files/includes/projects/links.php.md) - in [projectLinks()](/code/functions/projectLinks.md), [projectLinkAdd()](/code/functions/projectLinkAdd.md), [projectLinkRemove()](/code/functions/projectLinkRemove.md), [projectLinkSearch()](/code/functions/projectLinkSearch.md)


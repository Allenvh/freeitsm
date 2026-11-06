# apiProjectItem()

One scope item of a project, or answers 404 and stops.

```php
function apiProjectItem(PDO $conn, int $pid, int $itemId): array
```

Defined in [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md), line 412.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$pid` | `int` |  | the projects id |
| `$itemId` | `int` |  | the scope item id, which must belong to that project |

**Returns:** `array`

## Calls

[apiError()](/code/functions/apiError.md)

## Called from

- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiProjectItemsCreate()](/code/functions/apiProjectItemsCreate.md), [apiProjectItemsUpdate()](/code/functions/apiProjectItemsUpdate.md), [apiProjectItemsDelete()](/code/functions/apiProjectItemsDelete.md)


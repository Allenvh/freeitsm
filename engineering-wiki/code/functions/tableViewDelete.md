# tableViewDelete()

Delete a view. Owner only.

```php
function tableViewDelete(PDO $conn, int $analystId, int $viewId): void
```

Defined in [includes/table_views.php](/code/files/includes/table_views.php.md), line 221.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$viewId` | `int` |  | the view (table_views.id) to delete; must be the analyst's own |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 223 | `tableViewDelete` | **delete** [table_views](/code/tables/table_views.md) |  |

## Called from

- [api/table-views/delete.php](/code/files/api/table-views/delete.php.md)


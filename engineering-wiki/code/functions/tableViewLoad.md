# tableViewLoad()

One view, if this analyst may see it.

```php
function tableViewLoad(PDO $conn, int $analystId, int $viewId): array
```

Defined in [includes/table_views.php](/code/files/includes/table_views.php.md), line 124.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$viewId` | `int` |  | the view (table_views.id) to load |

**Returns:** `array`

## Calls

[tableViewSelect()](/code/functions/tableViewSelect.md), [tableViewShape()](/code/functions/tableViewShape.md), [tableViewVisibleClause()](/code/functions/tableViewVisibleClause.md)

## Called from

- [api/table-views/use.php](/code/files/api/table-views/use.php.md)
- [includes/table_views.php](/code/files/includes/table_views.php.md) - in [tableViewTouch()](/code/functions/tableViewTouch.md)


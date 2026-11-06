# tableViewShape()

Coerce the types the browser cares about.

```php
function tableViewShape(array $r, int $analystId): array
```

Defined in [includes/table_views.php](/code/files/includes/table_views.php.md), line 74.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$r` | `array` |  | a table_views row from `tableViewSelect()` |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Called from

- [includes/table_views.php](/code/files/includes/table_views.php.md) - in [tableViewList()](/code/functions/tableViewList.md), [tableViewLoad()](/code/functions/tableViewLoad.md)


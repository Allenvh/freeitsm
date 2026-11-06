# tableViewTouch()

Stamp a view as used.

⚠️ One timestamp for the view, not one per reader. On a shared view this
answers "is anybody still using this?", which is the question that decides
whether it can be deleted. "When did I last use it?" would need a row per
person per view to answer, and nobody has asked that.

```php
function tableViewTouch(PDO $conn, int $analystId, int $viewId): void
```

Defined in [includes/table_views.php](/code/files/includes/table_views.php.md), line 240.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$viewId` | `int` |  | the view (table_views.id) just opened |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 243 | `tableViewTouch` | **update** [table_views](/code/tables/table_views.md) (`last_used_datetime`) |  |

## Calls

[tableViewLoad()](/code/functions/tableViewLoad.md)

## Called from

- [api/table-views/use.php](/code/files/api/table-views/use.php.md)


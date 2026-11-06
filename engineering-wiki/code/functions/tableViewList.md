# tableViewList()

Views on one table that this analyst can see, newest-used first.

`$q` searches the name, the description and the owner's name — "that one Dave
made" is how people look for a view they did not write.

```php
function tableViewList(PDO $conn, int $analystId, string $tableKey, string $q = ''): array
```

Defined in [includes/table_views.php](/code/files/includes/table_views.php.md), line 94.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$tableKey` | `string` |  | which table the views belong to: 'assets', 'tasks', 'calendar', 'changes' or 'domains' |
| `$q` | `string` | `''` | search text matched against the name, description and owner's name; '' for all |

**Returns:** `array`

## Calls

[tableViewSelect()](/code/functions/tableViewSelect.md), [tableViewShape()](/code/functions/tableViewShape.md), [tableViewVisibleClause()](/code/functions/tableViewVisibleClause.md)

## Called from

- [api/table-views/list.php](/code/files/api/table-views/list.php.md)


# tableViewVisibleClause()

The WHERE fragment and arguments that limit views to what this analyst may see: their own, ones shared with a team they are in, and public ones.

⚠️ BRACKETED as one group. Callers append it to a WHERE that already has a
table_key condition, and an unbracketed set of alternatives would make that
condition apply to the last branch only — so a tasks view would appear on the
asset table. The same shape has bitten this codebase before.

```php
function tableViewVisibleClause(PDO $conn, int $analystId): array
```

Defined in [includes/table_views.php](/code/files/includes/table_views.php.md), line 42.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Calls

[tableViewTeamIds()](/code/functions/tableViewTeamIds.md)

## Called from

- [includes/table_views.php](/code/files/includes/table_views.php.md) - in [tableViewList()](/code/functions/tableViewList.md), [tableViewLoad()](/code/functions/tableViewLoad.md)


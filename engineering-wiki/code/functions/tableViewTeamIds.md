# tableViewTeamIds()

The teams this analyst belongs to. Empty array is a valid answer.

```php
function tableViewTeamIds(PDO $conn, int $analystId): array
```

Defined in [includes/table_views.php](/code/files/includes/table_views.php.md), line 26.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 | `tableViewTeamIds` | read [analyst_teams](/code/tables/analyst_teams.md) |  |

## Called from

- [api/table-views/list.php](/code/files/api/table-views/list.php.md)
- [includes/table_views.php](/code/files/includes/table_views.php.md) - in [tableViewVisibleClause()](/code/functions/tableViewVisibleClause.md), [tableViewSave()](/code/functions/tableViewSave.md)


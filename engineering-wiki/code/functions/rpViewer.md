# rpViewer()

The teams and department this analyst counts as, for matching shares.

```php
function rpViewer(PDO $conn, int $analystId): array
```

Defined in [includes/report_packs/access.php](/code/files/includes/report_packs/access.php.md), line 31.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 | `rpViewer` | read [analyst_teams](/code/tables/analyst_teams.md) |  |
| 43 | `rpViewer` | read [analysts](/code/tables/analysts.md) |  |

## Calls

[rpNormDept()](/code/functions/rpNormDept.md)

## Called from

- [includes/report_packs/access.php](/code/files/includes/report_packs/access.php.md) - in [rpRole()](/code/functions/rpRole.md), [rpListPacks()](/code/functions/rpListPacks.md)


# rpListPacks()

Every pack this analyst can open, newest change first, with their role on each.

```php
function rpListPacks(PDO $conn, int $analystId): array
```

Defined in [includes/report_packs/access.php](/code/files/includes/report_packs/access.php.md), line 128.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** array<int,array>

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 134 | `rpListPacks` | read [report_pack_shares](/code/tables/report_pack_shares.md), read [report_packs](/code/tables/report_packs.md), read [analysts](/code/tables/analysts.md) |  |

## Calls

[rpShareMatchSql()](/code/functions/rpShareMatchSql.md), [rpViewer()](/code/functions/rpViewer.md)

## Called from

- [api/reporting/packs/list.php](/code/files/api/reporting/packs/list.php.md)


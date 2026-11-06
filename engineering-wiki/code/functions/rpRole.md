# rpRole()

This viewer's role on a pack: 'owner', 'edit', 'view', or null (no access, or no such pack - the caller says "not found" for both, so ids cannot be probed).

```php
function rpRole(PDO $conn, int $analystId, int $packId): ?string
```

Defined in [includes/report_packs/access.php](/code/files/includes/report_packs/access.php.md), line 93.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$packId` | `int` |  | the report_packs id |

**Returns:** `?string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 95 | `rpRole` | read [report_packs](/code/tables/report_packs.md) |  |
| 106 | `rpRole` | read [report_pack_shares](/code/tables/report_pack_shares.md) |  |

## Calls

[rpShareMatchSql()](/code/functions/rpShareMatchSql.md), [rpViewer()](/code/functions/rpViewer.md)

## Called from

- [api/reporting/packs/create.php](/code/files/api/reporting/packs/create.php.md)
- [api/reporting/packs/delete.php](/code/files/api/reporting/packs/delete.php.md)
- [api/reporting/packs/get.php](/code/files/api/reporting/packs/get.php.md)
- [api/reporting/packs/save.php](/code/files/api/reporting/packs/save.php.md)
- [api/reporting/packs/shares.php](/code/files/api/reporting/packs/shares.php.md)


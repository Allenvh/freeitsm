# rpListShares()

A pack's shares, with a display name for each target. Owner only.

```php
function rpListShares(PDO $conn, int $packId): array
```

Defined in [includes/report_packs/access.php](/code/files/includes/report_packs/access.php.md), line 175.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$packId` | `int` |  | the report_packs id |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 178 | `rpListShares` | read [report_pack_shares](/code/tables/report_pack_shares.md), read [analysts](/code/tables/analysts.md), read [teams](/code/tables/teams.md) |  |

## Called from

- [api/reporting/packs/shares.php](/code/files/api/reporting/packs/shares.php.md)


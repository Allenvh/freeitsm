# rpSaveShares()

Replace a pack's shares with `$shares` (the whole list, as the dialog sends it). Each entry is validated against what exists; unknown targets are dropped, never stored, so a share cannot point at an id that a later analyst or team would inherit.

```php
function rpSaveShares(PDO $conn, int $packId, int $ownerId, array $shares): void
```

Defined in [includes/report_packs/access.php](/code/files/includes/report_packs/access.php.md), line 213.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$packId` | `int` |  | the report_packs id |
| `$ownerId` | `int` |  | the pack owner's analyst id - a share to them is dropped |
| `$shares` | `array` |  | the whole list from the share dialog (untrusted): each {type: analyst\|team\|department, id or value, can_edit} |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 237 | `rpSaveShares` | **delete** [report_pack_shares](/code/tables/report_pack_shares.md) |  |
| 238 | `rpSaveShares` | **insert** [report_pack_shares](/code/tables/report_pack_shares.md) (`pack_id`, `target_type`, `target_id`, `target_value`, `can_edit`) |  |

## Calls

[rpNormDept()](/code/functions/rpNormDept.md)

## Called from

- [api/reporting/packs/shares.php](/code/files/api/reporting/packs/shares.php.md)


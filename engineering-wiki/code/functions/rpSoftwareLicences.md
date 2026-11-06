# rpSoftwareLicences()

Licences, optionally only those renewing in the range. Licence keys are NEVER included - a report is printed, emailed and left on desks.

```php
function rpSoftwareLicences(PDO $conn, int $analystId, array $o, array $range, $tenant): array
```

Defined in [includes/report_packs/blocks_software.php](/code/files/includes/report_packs/blocks_software.php.md), line 110.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$o` | `array` |  | the block's cleaned options: renewing (only licences renewing in the range) |
| `$range` | `array` |  | the pack's range from `rpResolveRange()` - from_date and to_date are used |
| `$tenant` |  |  | the pack's company (not used - licences are not split by company) |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 114 | `rpSoftwareLicences` | read [software_licences](/code/tables/software_licences.md), read [software_inventory_apps](/code/tables/software_inventory_apps.md) |  |

## Calls

[rpFmtDate()](/code/functions/rpFmtDate.md), [t()](/code/functions/t.md)

*Nothing calls this function by name.*


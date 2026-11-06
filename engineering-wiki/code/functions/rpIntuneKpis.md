# rpIntuneKpis()

Intune block: KPI tiles - device count, percentage compliant and encrypted, and devices not synced for 30 days.

```php
function rpIntuneKpis(PDO $conn, int $analystId, array $o, array $range, $tenant): array
```

Defined in [includes/report_packs/blocks_assets.php](/code/files/includes/report_packs/blocks_assets.php.md), line 140.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$o` | `array` |  | the block's cleaned options (none are read) |
| `$range` | `array` |  | the pack's range (not used - a snapshot of today) |
| `$tenant` |  |  | the pack's company (not used - Intune devices are not split by company) |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 142 | `rpIntuneKpis` | read [intune_devices](/code/tables/intune_devices.md) |  |

## Calls

[t()](/code/functions/t.md)

*Nothing calls this function by name.*


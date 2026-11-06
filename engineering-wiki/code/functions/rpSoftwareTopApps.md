# rpSoftwareTopApps()

Software block: a chart of the most installed applications, by number of machines. A snapshot of today.

```php
function rpSoftwareTopApps(PDO $conn, int $analystId, array $o, array $range, $tenant): array
```

Defined in [includes/report_packs/blocks_software.php](/code/files/includes/report_packs/blocks_software.php.md), line 56.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$o` | `array` |  | the block's cleaned options: system (include system components), limit, chart |
| `$range` | `array` |  | the pack's range (not used - a snapshot of today) |
| `$tenant` |  |  | the pack's company: 'active', 'all' or a company id |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 59 | `rpSoftwareTopApps` | read [software_inventory_apps](/code/tables/software_inventory_apps.md), read [software_inventory_detail](/code/tables/software_inventory_detail.md) |  |

## Calls

[rpSoftwareScope()](/code/functions/rpSoftwareScope.md), [t()](/code/functions/t.md)

*Nothing calls this function by name.*


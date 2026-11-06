# rpStatusUptime()

Service status block: each chosen service's uptime over the period, with an optional daily strip and incident list.

```php
function rpStatusUptime(PDO $conn, int $analystId, array $o, array $range, $tenant): array
```

Defined in [includes/report_packs/blocks_status.php](/code/files/includes/report_packs/blocks_status.php.md), line 44.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$o` | `array` |  | the block's cleaned options: services (ids; [] for all), strip (daily bars), incidents (list them) |
| `$range` | `array` |  | the pack's range from `rpResolveRange()` |
| `$tenant` |  |  | the pack's company (not used - Service Status has no company) |

**Returns:** `array`

## Calls

[rpFmtDate()](/code/functions/rpFmtDate.md), [rpFmtDateTime()](/code/functions/rpFmtDateTime.md), [rpHumanDuration()](/code/functions/rpHumanDuration.md), [rpPercent()](/code/functions/rpPercent.md), [rpStatusServices()](/code/functions/rpStatusServices.md), [t()](/code/functions/t.md)

*Nothing calls this function by name.*


# rpStatusServices()

The services a block covers: the chosen ones, or every active one.

```php
function rpStatusServices(PDO $conn, array $ids): array
```

Defined in [includes/report_packs/blocks_status.php](/code/files/includes/report_packs/blocks_status.php.md), line 23.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ids` | `array` |  | status_services ids to narrow to; [] means every active service |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 25 | `rpStatusServices` | read [status_services](/code/tables/status_services.md) |  |

## Called from

- [includes/report_packs/blocks_status.php](/code/files/includes/report_packs/blocks_status.php.md) - in [rpStatusUptime()](/code/functions/rpStatusUptime.md), [rpStatusUptimeTable()](/code/functions/rpStatusUptimeTable.md), [rpStatusKpis()](/code/functions/rpStatusKpis.md)


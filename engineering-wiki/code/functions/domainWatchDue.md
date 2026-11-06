# domainWatchDue()

The weekly watches keep their "last scanned" in the domain's audit trail rather than two more columns: one row per scan, source 'check'. A domain with no such row in `$days` days is due.

```php
function domainWatchDue(PDO $conn, string $marker, int $days, string $where): array
```

Defined in [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md), line 127.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$marker` | `string` |  | the domain_audit field_name the watch records, 'ct_scanned' or 'lookalikes_scanned' |
| `$days` | `int` |  | how many days since the last marker make a domain due |
| `$where` | `string` |  | an extra SQL condition on domains (alias d) - interpolated, so only literals from here |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 130 | `domainWatchDue` | read [domains](/code/tables/domains.md), read [domain_audit](/code/tables/domain_audit.md) |  |

## Called from

- [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md) - in [domainScheduledRun()](/code/functions/domainScheduledRun.md)


# sla_cron_log_start()

Helper — record a run row. Returns the inserted id (or null if logging fails).

```php
function sla_cron_log_start(PDO $conn, bool $isCli, ?string $clientIp): ?int
```

Defined in [cron/sla_breach_check.php](/code/files/cron/sla_breach_check.php.md), line 49.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$isCli` | `bool` |  | true when run from the command line (logged as 'cli'), false for an HTTP call ('http') |
| `$clientIp` | `?string` |  | the caller's REMOTE_ADDR for an HTTP call; null from the command line |

**Returns:** `?int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 51 | `sla_cron_log_start` | **insert** [sla_cron_runs](/code/tables/sla_cron_runs.md) (`started_at`, `invocation`, `client_ip`, `outcome`) |  |

## Called from

- [cron/sla_breach_check.php](/code/files/cron/sla_breach_check.php.md)


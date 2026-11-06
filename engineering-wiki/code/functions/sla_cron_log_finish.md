# sla_cron_log_finish()

Completes this run's sla_cron_runs row with its end time, duration, outcome, counts and notes; does nothing when the start row was never written.

```php
function sla_cron_log_finish(
    PDO $conn,
    ?int $runId,
    string $outcome,
    array $counts = [],
    ?string $notes = null
): void
```

Defined in [cron/sla_breach_check.php](/code/files/cron/sla_breach_check.php.md), line 68.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$runId` | `?int` |  | the row id from `sla_cron_log_start()`; null when that insert failed |
| `$outcome` | `string` |  | ok, error, auth_failed, config_missing or rate_limited |
| `$counts` | `array` | `[]` | array{sent?:int,skipped?:int,errors?:int} - missing keys are logged as 0 |
| `$notes` | `?string` | `null` | free text for the log (cut to 65,000 characters); null or empty stores NULL |

**Returns:** `void`

**Writes:** sla_cron_runs(ended_at, duration_ms, outcome, sent_count, skipped_count, error_count, notes) - the run log shown in SLA settings

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 71 | `sla_cron_log_finish` | **update** [sla_cron_runs](/code/tables/sla_cron_runs.md) (`ended_at`, `duration_ms`, `outcome`, `sent_count`, `skipped_count`, `error_count`, `notes`) |  |

## Called from

- [cron/sla_breach_check.php](/code/files/cron/sla_breach_check.php.md)


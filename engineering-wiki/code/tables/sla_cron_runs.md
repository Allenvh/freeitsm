# sla_cron_runs

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `started_at` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `ended_at` | `DATETIME NULL` |  |
| `duration_ms` | `INT NULL` |  |
| `invocation` | `ENUM('cli','http') NOT NULL` |  |
| `client_ip` | `VARCHAR(45) NULL` |  |
| `outcome` | `ENUM('ok','auth_failed','rate_limited','error','config_missing') NOT NULL` |  |
| `sent_count` | `INT NULL DEFAULT 0` |  |
| `skipped_count` | `INT NULL DEFAULT 0` |  |
| `error_count` | `INT NULL DEFAULT 0` |  |
| `notes` | `TEXT NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [cron/sla_breach_check.php](/code/files/cron/sla_breach_check.php.md):51 | `sla_cron_log_start` | insert | `started_at`, `invocation`, `client_ip`, `outcome` |  |
| [cron/sla_breach_check.php](/code/files/cron/sla_breach_check.php.md):71 | `sla_cron_log_finish` | update | `ended_at`, `duration_ms`, `outcome`, `sent_count`, `skipped_count`, `error_count`, `notes` |  |
| [cron/sla_breach_check.php](/code/files/cron/sla_breach_check.php.md):187 |  | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/tickets/get_sla_cron_runs.php](/code/files/api/tickets/get_sla_cron_runs.php.md) | L27 |  |
| [cron/sla_breach_check.php](/code/files/cron/sla_breach_check.php.md) | L112, L149 |  |


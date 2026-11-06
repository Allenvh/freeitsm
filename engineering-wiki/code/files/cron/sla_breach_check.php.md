# cron/sla_breach_check.php

> PHP - 223 lines

SLA Breach Check — cron entry point.

Walks every open SLA-tracked ticket, fires warning + breach email
notifications according to the rules configured under
Tickets > Settings > SLA > Breach Notifications. Dedups via the
sla_notifications_sent table so each (ticket, target, trigger) fires
at most once.

Run on a schedule (every 5 minutes is the sweet spot — frequent enough
to catch breaches promptly, sparse enough to avoid hammering the
mailbox API). See docs/sla-cron-setup.md for Windows Task Scheduler +
Linux cron configuration.

SECURITY LAYERS (HTTP invocation only):
  1. Shared-secret token via ?token=<value> matching sla_cron_token in
     system_settings. ~128 bits of entropy, `hash_equals()` comparison.
  2. Per-IP failed-auth lockout: >10 wrong-token attempts from the same
     IP in the past hour triggers a 1-hour 429 lockout.
  3. Min interval between successful runs (sla_cron_min_interval_seconds,
     default 30s) — defeats double-scheduling, runaway loops, post-leak
     abuse. CLI invocations also honour this.

LOGGING: every invocation (accepted or rejected) is logged to
sla_cron_runs with started_at / ended_at / outcome / counts / IP.
Pruned at end-of-run based on sla_cron_log_retention_days (default 30).

## Functions

| Function | Line | What it does |
|---|--:|---|
| [sla_cron_log_start()](/code/functions/sla_cron_log_start.md) | 49 | Helper — record a run row. Returns the inserted id (or null if logging fails). |
| [sla_cron_log_finish()](/code/functions/sla_cron_log_finish.md) | 68 | Completes this run's sla_cron_runs row with its end time, duration, outcome, counts and notes; does nothing when the start row was never written. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 51 | `sla_cron_log_start` | **insert** [sla_cron_runs](/code/tables/sla_cron_runs.md) (`started_at`, `invocation`, `client_ip`, `outcome`) |  |
| 71 | `sla_cron_log_finish` | **update** [sla_cron_runs](/code/tables/sla_cron_runs.md) (`ended_at`, `duration_ms`, `outcome`, `sent_count`, `skipped_count`, `error_count`, `notes`) |  |
| 100 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 112 |  | read [sla_cron_runs](/code/tables/sla_cron_runs.md) |  |
| 149 |  | read [sla_cron_runs](/code/tables/sla_cron_runs.md) |  |
| 187 |  | **delete** [sla_cron_runs](/code/tables/sla_cron_runs.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md), [sla_run_breach_check()](/code/functions/sla_run_breach_check.md)


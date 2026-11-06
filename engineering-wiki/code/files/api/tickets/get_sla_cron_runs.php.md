# api/tickets/get_sla_cron_runs.php

> API endpoint - 54 lines

API: Recent SLA cron run history for the Cron Activity panel.

GET ?limit=20 (cap 100) — returns most-recent runs first.

Response: { runs: [...], settings: { min_interval_seconds, retention_days } }

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_sla_cron_runs.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET limit` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 27 |  | read [sla_cron_runs](/code/tables/sla_cron_runs.md) |  |
| 37 |  | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


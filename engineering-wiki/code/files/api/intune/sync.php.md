# api/intune/sync.php

> API endpoint - 87 lines

API: kick off an InTune sync job.

Inserts a row into intune_sync_jobs, spawns the CLI worker, returns the
job id so the UI can poll sync_status.php.

## The endpoint

| | |
|---|---|
| URL | `api/intune/sync.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_INTUNE (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 |  | **update** [intune_sync_jobs](/code/tables/intune_sync_jobs.md) (`status`, `message`, `finished_datetime`) |  |
| 55 |  | read [intune_sync_jobs](/code/tables/intune_sync_jobs.md) |  |
| 63 |  | **insert** [intune_sync_jobs](/code/tables/intune_sync_jobs.md) (`started_datetime`, `status`, `total`, `processed`, `message`) |  |
| 72 |  | **update** [intune_sync_jobs](/code/tables/intune_sync_jobs.md) (`status`, `message`, `finished_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/intune.php](/code/files/includes/intune.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [intuneGetSettings()](/code/functions/intuneGetSettings.md), [intuneSpawnWorker()](/code/functions/intuneSpawnWorker.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


# api/intune/app_sync_job_status.php

> API endpoint - 87 lines

API: progress poll for an InTune app-sync job. Returns the job header plus a roll-up of child statuses so the UI can show "X of Y assets done, Z failed".

GET ?id=<jobId> for a specific job; no id returns the latest job (or null).

## The endpoint

| | |
|---|---|
| URL | `api/intune/app_sync_job_status.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_INTUNE (requireCapabilityJson) |
| Reads | `GET id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 30 |  | read [intune_app_sync_jobs](/code/tables/intune_app_sync_jobs.md) |  |
| 36 |  | read [intune_app_sync_jobs](/code/tables/intune_app_sync_jobs.md) |  |
| 51 |  | read [intune_app_sync_job_assets](/code/tables/intune_app_sync_job_assets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


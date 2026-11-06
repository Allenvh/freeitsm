# api/intune/sync_status.php

> API endpoint - 70 lines

API: progress poll for an InTune sync job. GET ?id=<jobId> returns that job; no id returns the latest job (or null).

## The endpoint

| | |
|---|---|
| URL | `api/intune/sync_status.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_INTUNE (requireCapabilityJson) |
| Reads | `GET id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 |  | read [intune_sync_jobs](/code/tables/intune_sync_jobs.md) |  |
| 34 |  | read [intune_sync_jobs](/code/tables/intune_sync_jobs.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


# api/assets/import_history.php

> API endpoint - 72 lines

API: past runs, one run's rows, and the holding area.

?runs=1            recent live runs
?run_id=N          one run's per-row outcomes
?unresolved=1      rows still needing attention, across every run

## The endpoint

| | |
|---|---|
| URL | `api/assets/import_history.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |
| Reads | `GET run_id`, `GET action`, `GET unresolved`, `GET runs` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 50 |  | read [asset_import_runs](/code/tables/asset_import_runs.md), read [analysts](/code/tables/analysts.md) |  |
| 60 |  | read [asset_import_run_entries](/code/tables/asset_import_run_entries.md), read [asset_import_runs](/code/tables/asset_import_runs.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/services/asset_import.php](/code/files/includes/services/asset_import.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


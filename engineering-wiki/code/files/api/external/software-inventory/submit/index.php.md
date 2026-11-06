# api/external/software-inventory/submit/index.php

> API endpoint - 471 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Asset Reconciliation Developer Guide](https://github.com/edmozley/freeitsm/wiki/Asset-Reconciliation-Developer-Guide)

External API: the software inventory agent posts one machine's installed software here.

Authenticated by an API key in the Authorization header (apikeys); the key's company
owns the machine. The machine is matched or created through AssetsService (the same
reconcile rules as every inventory source), then software_inventory_apps and
software_inventory_detail are brought in line with the list - apps no longer reported
are removed from this machine. Every outcome is logged to software_inventory_log.

## The endpoint

| | |
|---|---|
| URL | `api/external/software-inventory/submit/index.php` |
| Protected by | Signed-in analyst (session check) (via includes/service_context.php); Signed-in analyst (session check) (via includes/notifications_router.php) |
| Input | Hostname  the machine's name (JSON body) |
| Input | Software  the installed applications - each with Display Name, Publisher, Display Version, Install Date and so on |
| Output | {status, hostname, counts of apps and detail rows inserted, updated and deleted}, or {error} |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [logApiResponse()](/code/functions/logApiResponse.md) | 122 | Helper function to log errors/responses |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 60 |  | read [apikeys](/code/tables/apikeys.md) |  |
| 64 |  | read [apikeys](/code/tables/apikeys.md) |  |
| 124 | `logApiResponse` | **insert** [software_inventory_log](/code/tables/software_inventory_log.md) (`host_id`, `api_response`) |  |
| 149 |  | **update** [assets](/code/tables/assets.md) (`last_seen`) |  |
| 168 |  | read [software_inventory_detail](/code/tables/software_inventory_detail.md) |  |
| 234 |  | read [software_inventory_apps](/code/tables/software_inventory_apps.md) |  |
| 287 |  | **update** [software_inventory_apps](/code/tables/software_inventory_apps.md) (`display_name`, `publisher`) |  |
| 294 |  | **insert** [software_inventory_apps](/code/tables/software_inventory_apps.md) (`display_name`, `publisher`) |  |
| 298 |  | read [software_inventory_apps](/code/tables/software_inventory_apps.md) |  |
| 352 |  | read [software_inventory_detail](/code/tables/software_inventory_detail.md) |  |
| 358 |  | **update** [software_inventory_detail](/code/tables/software_inventory_detail.md) (`display_version`, `install_date`, `uninstall_string`, `install_location`, `estimated_size`, `system_component`, `last_seen`) |  |
| 383 |  | **insert** [software_inventory_detail](/code/tables/software_inventory_detail.md) (`host_id`, `app_id`, `display_version`, `install_date`, `uninstall_string`, `install_location`, `estimated_size`, `system_component`, `created_at`, `last_seen`) |  |
| 432 |  | **delete** [software_inventory_detail](/code/tables/software_inventory_detail.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/assets.php](/code/files/includes/services/assets.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)


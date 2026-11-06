# api/assets/get_vcenter.php

> API endpoint - 358 lines

API Endpoint: Sync VM data from vCenter REST API Connects to vCenter, fetches all VMs with specs, saves to servers table

## The endpoint

| | |
|---|---|
| URL | `api/assets/get_vcenter.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [vcenterAuth()](/code/functions/vcenterAuth.md) | 260 | Authenticate with vCenter REST API |
| [vcenterGet()](/code/functions/vcenterGet.md) | 302 | GET request to vCenter REST API |
| [vcenterLogout()](/code/functions/vcenterLogout.md) | 343 | Logout from vCenter session |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 30 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 208 |  | read [servers](/code/tables/servers.md) |  |
| 213 |  | **update** [servers](/code/tables/servers.md) (`name`, `power_state`, `memory_gb`, `num_cpu`, `ip_address`, `hard_disk_size_gb`, `host`, `cluster`, `guest_os`, `raw_data`, `last_synced`) |  |
| 222 |  | **insert** [servers](/code/tables/servers.md) (`vm_id`, `name`, `power_state`, `memory_gb`, `num_cpu`, `ip_address`, `hard_disk_size_gb`, `host`, `cluster`, `guest_os`, `raw_data`, `last_synced`) |  |
| 236 |  | **delete** [servers](/code/tables/servers.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)


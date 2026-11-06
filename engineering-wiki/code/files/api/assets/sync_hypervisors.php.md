# api/assets/sync_hypervisors.php

> API endpoint - 61 lines

API Endpoint: sync every active Proxmox VE and VMware Cloud Director server now - the Servers page's Sync button, after it has run vCenter. POST → { success, results: [{source, name, status, message}] }

Added when PR #167 was merged. The PR's button fetched the two server lists
and called each server's sync from the browser. Those endpoints need the
settings permissions (assets.proxmox, assets.vcloud), so for anyone who only
uses the Servers page the lists came back 403 and both sources were silently
skipped - the button said "synced" and synced nothing new.

vCenter settled this question already: its sync (get_vcenter.php) runs on
module access, because the Servers page is operational and syncing changes
no configuration. This follows it. Configuring servers, testing them and
"Show VMs" still need the settings permissions.

## The endpoint

| | |
|---|---|
| URL | `api/assets/sync_hypervisors.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md), [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


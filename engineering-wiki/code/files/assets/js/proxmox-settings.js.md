# assets/js/proxmox-settings.js

> JavaScript - 183 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Proxmox and Cloud Director Developer Guide](https://github.com/edmozley/freeitsm/wiki/Proxmox-and-Cloud-Director-Developer-Guide)

Proxmox VE servers on the asset-management settings page: list, add/edit, delete, test, sync now, and the VMs a sync found. Talks to api/assets/proxmox_*.php. Passwords are never read back: the form shows a placeholder and a blank field keeps the stored password.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `T(k, p)` | 16 | Translates a key under asset-management.proxmox.*, or returns the key when the i18n helper is not loaded. |
| `call(path, opts)` | 20 | Fetches an api/assets/ endpoint with the session cookie and returns the parsed JSON reply. |
| `statusBadge(s)` | 29 | Returns a coloured badge for a sync status (ok, warning, error), or "never" when there has been no sync. |
| `proxmoxLoad()` | 40 | Loads the Proxmox servers from proxmox_connections.php and renders the settings table, with Test, Sync, VMs, Edit and Delete buttons per row. |
| `proxmoxOpenForm(id)` | 74 | Opens the add / edit server dialog, filled from the cached server when id is given; the password field always starts blank. |
| `proxmoxCloseForm()` | 89 | Closes the add / edit server dialog. |
| `proxmoxSave(e)` | 97 | Saves the server dialog to proxmox_connections.php (action save), then closes it and reloads the list; a blank password keeps the stored one. |
| `proxmoxDelete(id)` | 121 | Asks for confirmation, then deletes a server through proxmox_connections.php and reloads the list. |
| `run(id, btn, action)` | 133 | Runs a test or sync for one server through proxmox_sync.php, disabling the button meanwhile, toasting the result and reloading the list. |
| `proxmoxTest(id, btn)` | 153 | Tests the connection to one server. |
| `proxmoxSync(id, btn)` | 155 | Syncs one server's VMs now. |
| `proxmoxShowVms(id)` | 158 | Shows the panel of VMs the last sync found for one server, from proxmox_vms.php. |

## Loaded by

- [asset-management/settings/index.php](/code/files/asset-management/settings/index.php.md)


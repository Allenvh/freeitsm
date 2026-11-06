# assets/js/vcloud-settings.js

> JavaScript - 189 lines

VMware Cloud Director servers on the asset-management settings page: list, add/edit, delete, test, sync now, and the VMs a sync found. Talks to api/assets/vcloud_*.php. Passwords are never read back: the form shows a placeholder and a blank field keeps the stored password.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `T(k, p)` | 11 | Translates a key under asset-management.vcloud, or returns the key when no translator is loaded. |
| `call(path, opts)` | 15 | Fetches a JSON endpoint under api/assets/ with the session cookie and returns the parsed body. |
| `statusBadge(s)` | 21 | Returns the coloured badge HTML for a last-sync status (ok, warning, error, or never synced). |
| `vcloudLoad()` | 29 | Loads the Cloud Director servers from vcloud_connections.php and draws the settings table with each row's buttons. |
| `vcloudOpenForm(id)` | 62 | Opens the add/edit modal, filled from the server with this id or with defaults for a new one; the password field is always left blank. |
| `vcloudCloseForm()` | 79 | Closes the add/edit server modal. |
| `vcloudSave(e)` | 84 | Saves the modal's server through vcloud_connections.php; a blank password keeps the stored one. |
| `vcloudDelete(id)` | 110 | Asks for confirmation, then deletes the server through vcloud_connections.php and reloads the table. |
| `run(id, btn, action)` | 121 | Runs a test or a sync for one server through vcloud_sync.php, disabling its button meanwhile, toasting the result and reloading the table. |
| `vcloudTest(id, btn)` | 141 | Tests the connection to one server. |
| `vcloudSync(id, btn)` | 143 | Syncs one server now. |
| `vcloudShowVms(id)` | 146 | Shows the VMs (and edge gateways) the last sync of one server found, from vcloud_vms.php. |
| `renderEdges(edges)` | 169 | Edge gateways were synced and returned, but nothing showed them (added at merge). |

## Loaded by

- [asset-management/settings/index.php](/code/files/asset-management/settings/index.php.md)


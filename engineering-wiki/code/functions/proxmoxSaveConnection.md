# proxmoxSaveConnection()

Save a server from the settings form. Returns its id.

Moved here from api/assets/proxmox_connections.php at merge time so that the
rules are in one place, and tests/hypervisor-sync.php runs the real ones.

TRAP: the password field holds the API token SECRET (or the user's password).
  It is stored only through `encryptValue()`, never returned to the browser
  (proxmoxConnectionOut() says has_password and nothing more), and a blank or
  all-asterisks value on edit keeps the stored one.

```php
function proxmoxSaveConnection(PDO $conn, array $in): int
```

Defined in [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md), line 482.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the settings form's fields (untrusted): id, name, host, username, password, verify_ssl, is_active, sync_interval_minutes |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 504 | `proxmoxSaveConnection` | read [proxmox_connections](/code/tables/proxmox_connections.md) |  |
| 519 | `proxmoxSaveConnection` | **update** [proxmox_connections](/code/tables/proxmox_connections.md) (`name`, `host`, `username`, `password_enc`, `verify_ssl`, `is_active`, `sync_interval_minutes`) |  |
| 525 | `proxmoxSaveConnection` | **insert** [proxmox_connections](/code/tables/proxmox_connections.md) (`name`, `host`, `username`, `password_enc`, `verify_ssl`, `is_active`, `sync_interval_minutes`) |  |

## Calls

[encryptValue()](/code/functions/encryptValue.md)

## Called from

- [api/assets/proxmox_connections.php](/code/files/api/assets/proxmox_connections.php.md)


# proxmoxLoadConnection()

Decrypted connection row, or throws. Never returns the password to a caller that will display it.

```php
function proxmoxLoadConnection(PDO $conn, int $connectionId): array
```

Defined in [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md), line 36.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$connectionId` | `int` |  | the proxmox_connections id |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 | `proxmoxLoadConnection` | read [proxmox_connections](/code/tables/proxmox_connections.md) |  |

## Calls

[decryptValue()](/code/functions/decryptValue.md)

## Called from

- [api/assets/proxmox_sync.php](/code/files/api/assets/proxmox_sync.php.md)
- [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md) - in [proxmoxSyncConnection()](/code/functions/proxmoxSyncConnection.md)


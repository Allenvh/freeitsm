# proxmoxLogin()

Authentication for this server: an API token header, or a login ticket for a user.

```php
function proxmoxLogin(array $c): array
```

Defined in [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md), line 64.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$c` | `array` |  | the connection row from `proxmoxLoadConnection()`, password decrypted |

**Returns:** `array`

## Calls

[proxmoxCall()](/code/functions/proxmoxCall.md), [proxmoxIsApiToken()](/code/functions/proxmoxIsApiToken.md)

## Called from

- [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md) - in [proxmoxTestConnection()](/code/functions/proxmoxTestConnection.md), [proxmoxSyncConnection()](/code/functions/proxmoxSyncConnection.md)


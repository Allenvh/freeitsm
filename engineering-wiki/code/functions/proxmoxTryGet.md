# proxmoxTryGet()

GET a resource and return its data, or null if this node/VM cannot answer (not an error for the whole sync).

```php
function proxmoxTryGet(array $c, array $auth, string $path)
```

Defined in [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md), line 131.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$c` | `array` |  | the connection row |
| `$auth` | `array` |  | what `proxmoxLogin()` returned |
| `$path` | `string` |  | the path after '/api2/json', e.g. '/cluster/status' |

## Calls

[proxmoxCall()](/code/functions/proxmoxCall.md)

## Called from

- [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md) - in [proxmoxSyncConnection()](/code/functions/proxmoxSyncConnection.md), [proxmoxVmDetails()](/code/functions/proxmoxVmDetails.md)


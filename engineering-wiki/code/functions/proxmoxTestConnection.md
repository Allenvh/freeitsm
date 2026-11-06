# proxmoxTestConnection()

Test the connection without writing anything: login, then read the node list.

```php
function proxmoxTestConnection(array $c): string
```

Defined in [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md), line 145.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$c` | `array` |  | the connection row from `proxmoxLoadConnection()`, password decrypted |

**Returns:** `string`

## Calls

[proxmoxCall()](/code/functions/proxmoxCall.md), [proxmoxLogin()](/code/functions/proxmoxLogin.md)

## Called from

- [api/assets/proxmox_sync.php](/code/files/api/assets/proxmox_sync.php.md)

## Traps

> [!WARNING]
> **Line 148, in `proxmoxTestConnection`:** proxmoxCall, not proxmoxTryGet - the real reason must reach the admin. With a token there is no separate login call, so the node list is the first request; swallowing its error turned "could not reach the server" into "check the user's permissions" (found at merge, by Testing a server that does not exist).


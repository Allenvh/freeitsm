# proxmoxCall()

One API call. `$auth` is null for the login call itself. Throws with a readable message.

```php
function proxmoxCall(
    array $c,
    string $method,
    string $path,
    ?array $form = null,
    ?array $auth = null
): array
```

Defined in [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md), line 89.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$c` | `array` |  | the connection row - host and verify_ssl are read |
| `$method` | `string` |  | the HTTP method, e.g. 'GET' or 'POST' |
| `$path` | `string` |  | the API path from the host, starting '/api2/json/...' |
| `$form` | `?array` | `null` | form fields to send as the body; null for none |
| `$auth` | `?array` | `null` | what `proxmoxLogin()` returned (a token header, or ticket and csrf); null for the login call itself |

**Returns:** `array`

## Called from

- [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md) - in [proxmoxLogin()](/code/functions/proxmoxLogin.md), [proxmoxTryGet()](/code/functions/proxmoxTryGet.md), [proxmoxTestConnection()](/code/functions/proxmoxTestConnection.md), [proxmoxSyncConnection()](/code/functions/proxmoxSyncConnection.md)


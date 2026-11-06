# proxmoxIsApiToken()

A username containing "!" is an API token: "user@realm!tokenid", with the token's secret typed into the password field. Token auth needs no login and no CSRF token.

```php
function proxmoxIsApiToken(array $c): bool
```

Defined in [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md), line 54.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$c` | `array` |  | the connection row - username is read |

**Returns:** `bool`

## Called from

- [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md) - in [proxmoxLogin()](/code/functions/proxmoxLogin.md)


# vcenterLogout()

> [!NOTE]
> Defined in 2 places (each behind a function_exists() check, or in files never loaded together): [api/assets/debug_vcenter.php](/code/files/api/assets/debug_vcenter.php.md), [api/assets/get_vcenter.php](/code/files/api/assets/get_vcenter.php.md)

Logout from vCenter session

```php
function vcenterLogout($baseUrl, $sessionToken)
```

Defined in [api/assets/debug_vcenter.php](/code/files/api/assets/debug_vcenter.php.md), line 263.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$baseUrl` |  |  | the vCenter address with scheme and no trailing slash |
| `$sessionToken` |  |  | the session id returned by `vcenterAuth()`, ended by this call |

## Calls

[sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [api/assets/debug_vcenter.php](/code/files/api/assets/debug_vcenter.php.md)
- [api/assets/get_vcenter.php](/code/files/api/assets/get_vcenter.php.md)


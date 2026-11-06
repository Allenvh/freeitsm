# vcenterAuth()

> [!NOTE]
> Defined in 2 places (each behind a function_exists() check, or in files never loaded together): [api/assets/debug_vcenter.php](/code/files/api/assets/debug_vcenter.php.md), [api/assets/get_vcenter.php](/code/files/api/assets/get_vcenter.php.md)

Authenticate with vCenter REST API

```php
function vcenterAuth($baseUrl, $user, $pass)
```

Defined in [api/assets/debug_vcenter.php](/code/files/api/assets/debug_vcenter.php.md), line 192.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$baseUrl` |  |  | the vCenter address with scheme and no trailing slash, e.g. https://vc.example.com |
| `$user` |  |  | the vCenter user name, sent as HTTP Basic auth |
| `$pass` |  |  | that user's password, in plain text |

## Calls

[sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [api/assets/debug_vcenter.php](/code/files/api/assets/debug_vcenter.php.md)
- [api/assets/get_vcenter.php](/code/files/api/assets/get_vcenter.php.md)


# maskDn()

> [!NOTE]
> Defined in 2 places (each behind a function_exists() check, or in files never loaded together): [api/system/debug-tools/D010_signin_methods.php](/code/files/api/system/debug-tools/D010_signin_methods.php.md), [api/system/debug-tools/D011_directory_sync_readiness.php](/code/files/api/system/debug-tools/D011_directory_sync_readiness.php.md)

A DN carries a person's name in its leading component and the directory's shape in the rest. The shape is what you need to diagnose, the name is not — so mask every attribute VALUE and keep every attribute NAME and the commas. "CN=svc_freeitsm,OU=Service Accounts,DC=cnss,DC=local" -> "CN=sv******m,OU=Se******s,DC=cn***,DC=lo***"

```php
function maskDn($s)
```

Defined in [api/system/debug-tools/D010_signin_methods.php](/code/files/api/system/debug-tools/D010_signin_methods.php.md), line 110.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` |  |  | an LDAP distinguished name; empty gives (not set) |

## Calls

[mask()](/code/functions/mask.md)

## Called from

- [api/system/debug-tools/D010_signin_methods.php](/code/files/api/system/debug-tools/D010_signin_methods.php.md)
- [api/system/debug-tools/D011_directory_sync_readiness.php](/code/files/api/system/debug-tools/D011_directory_sync_readiness.php.md) - in [maskDnList()](/code/functions/maskDnList.md)


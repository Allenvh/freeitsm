# ssoJitCompanyReady()

True once Database Verification has added auth_providers.jit_company_access (3.5.0).

```php
function ssoJitCompanyReady(PDO $conn): bool
```

Defined in [includes/sso_identity.php](/code/files/includes/sso_identity.php.md), line 193.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 196 | `ssoJitCompanyReady` | read [auth_providers](/code/tables/auth_providers.md) |  |

## Called from

- [api/system/get_sso_providers.php](/code/files/api/system/get_sso_providers.php.md)
- [api/system/save_sso_provider.php](/code/files/api/system/save_sso_provider.php.md)
- [includes/sso_identity.php](/code/files/includes/sso_identity.php.md) - in [ssoApplyJitCompanies()](/code/functions/ssoApplyJitCompanies.md)


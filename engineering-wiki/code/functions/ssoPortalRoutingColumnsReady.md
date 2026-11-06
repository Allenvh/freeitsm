# ssoPortalRoutingColumnsReady()

Have the GH #147 portal routing columns (portal_email_domains, portal_show_button) been added yet? Same reason as `ssoJitColumnsReady()`: the portal login and the resolver must keep working on an upgraded install that has not run Database Verification. The two arrive together.

```php
function ssoPortalRoutingColumnsReady(PDO $conn): bool
```

Defined in [includes/sso_identity.php](/code/files/includes/sso_identity.php.md), line 116.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 120 | `ssoPortalRoutingColumnsReady` | read [auth_providers](/code/tables/auth_providers.md) |  |

## Called from

- [api/system/get_sso_providers.php](/code/files/api/system/get_sso_providers.php.md)
- [api/system/save_sso_provider.php](/code/files/api/system/save_sso_provider.php.md)
- [includes/sso_identity.php](/code/files/includes/sso_identity.php.md) - in [ssoPortalProviderForEmail()](/code/functions/ssoPortalProviderForEmail.md)
- [self-service/login.php](/code/files/self-service/login.php.md)


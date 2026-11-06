# ssoJitColumnsReady()

Have the discussion #155 provider columns (auto_create_analysts, analyst_fallback_mode, profile_sync_mode) been added yet?

🔴 An upgraded install has none of them until someone runs System →
Database Verification. Any query that NAMES one fails until then, and the
screens that read providers treat a failed query as "nothing there" - the
Authentication page would say "No providers yet". So every reader that
names them asks this first. The three arrive together, so one probe covers
all of them. Cached per request.

```php
function ssoJitColumnsReady(PDO $conn): bool
```

Defined in [includes/sso_identity.php](/code/files/includes/sso_identity.php.md), line 63.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 67 | `ssoJitColumnsReady` | read [auth_providers](/code/tables/auth_providers.md) |  |

## Called from

- [api/system/get_sso_providers.php](/code/files/api/system/get_sso_providers.php.md)
- [api/system/save_sso_provider.php](/code/files/api/system/save_sso_provider.php.md)
- [includes/sso_identity.php](/code/files/includes/sso_identity.php.md) - in [ssoAnalystProfileLocked()](/code/functions/ssoAnalystProfileLocked.md)
- [includes/users.php](/code/files/includes/users.php.md) - in [portalProfileAccess()](/code/functions/portalProfileAccess.md)


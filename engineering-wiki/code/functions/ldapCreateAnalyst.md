# ldapCreateAnalyst()

Create an analyst from directory attributes, assigned to this provider.

The local password is set to an unusable random hash: a directory-backed
analyst signs in via the directory, never a local password.

NOTE: deliberately mirrors `oidcCreateAnalyst()` in api/auth/oidc_callback.php
rather than sharing with it. Folding the two into one provisioning helper is
worthwhile, but is a refactor of working auth code and belongs in its own
change, not bundled with a new auth path.

```php
function ldapCreateAnalyst(
    PDO $conn,
    int $providerId,
    string $preferredUser,
    string $name,
    string $email,
    ?string $defaultModules
): int
```

Defined in [includes/ldap.php](/code/files/includes/ldap.php.md), line 555.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$providerId` | `int` |  | the auth_providers id the new analyst signs in through |
| `$preferredUser` | `string` |  | the directory username, used as the base of the new analyst's username |
| `$name` | `string` |  | the display name from the directory; '' uses the username |
| `$email` | `string` |  | the directory email address; '' is allowed |
| `$defaultModules` | `?string` |  | comma-separated module keys to grant; null or blank grants every module |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 560 | `ldapCreateAnalyst` | read [analysts](/code/tables/analysts.md) |  |
| 585 | `ldapCreateAnalyst` | **insert** [analysts](/code/tables/analysts.md) (`username`, `password_hash`, `full_name`, `email`, `is_active`, `created_datetime`, `auth_provider_id`, `can_access_all_modules`) |  |
| 592 | `ldapCreateAnalyst` | **insert** [analyst_modules](/code/tables/analyst_modules.md) (`analyst_id`, `module_key`) |  |

## Calls

[ssoApplyJitCompanies()](/code/functions/ssoApplyJitCompanies.md)

## Called from

- [includes/ldap.php](/code/files/includes/ldap.php.md) - in [ldapResolveAnalyst()](/code/functions/ldapResolveAnalyst.md)


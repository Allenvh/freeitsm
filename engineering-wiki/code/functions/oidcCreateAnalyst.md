# oidcCreateAnalyst()

Create a new analyst from SSO claims, assigned to the given provider. The local password is set to an unusable random hash (SSO users sign in via the IdP, not a local password).

```php
function oidcCreateAnalyst(
    PDO $conn,
    int $providerId,
    string $preferredUser,
    string $name,
    string $email,
    ?string $defaultModules,
    array $claims = [],
    string $syncMode = 'never'
): int
```

Defined in [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md), line 597.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$providerId` | `int` |  | the SSO provider the new analyst is assigned to |
| `$preferredUser` | `string` |  | the preferred_username claim (or email/sub), the base for the new username |
| `$name` | `string` |  | the display name; empty falls back to the username |
| `$email` | `string` |  | the email claim, lower-cased |
| `$defaultModules` | `?string` |  | the provider's comma-separated module keys; null or blank means every module |
| `$claims` | `array` | `[]` | the validated claims, read for job title, department and phones |
| `$syncMode` | `string` | `'never'` | the provider's profile_sync_mode - 'never' leaves the profile fields empty |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 603 | `oidcCreateAnalyst` | read [analysts](/code/tables/analysts.md) |  |
| 624 | `oidcCreateAnalyst` | **insert** [analysts](/code/tables/analysts.md) (`username`, `password_hash`, `full_name`, `email`, `job_title`, `department`, `phone`, `mobile`, `is_active`, `created_datetime`, `auth_provider_id`, `can_access_all_modules`) |  |
| 635 | `oidcCreateAnalyst` | **insert** [analyst_modules](/code/tables/analyst_modules.md) (`analyst_id`, `module_key`) |  |

## Calls

[oidcExtractProfileAttributes()](/code/functions/oidcExtractProfileAttributes.md), [ssoApplyJitCompanies()](/code/functions/ssoApplyJitCompanies.md), [ssoJitStartForTeams()](/code/functions/ssoJitStartForTeams.md)

## Called from

- [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md)


# ssoRememberGroups()

Record what a sign-in sent: the last sign-in line on the provider, and each group in sso_groups_seen for the dropdowns. Best effort - never fails a sign-in.

```php
function ssoRememberGroups(PDO $conn, int $providerId, string $who, array $groups): void
```

Defined in [includes/sso_claims.php](/code/files/includes/sso_claims.php.md), line 216.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$providerId` | `int` |  | the sign-in provider (auth_providers.id) |
| `$who` | `string` |  | who signed in, for the last-sign-in line: their email, preferred_username or sub claim |
| `$groups` | `array` |  | the group values the sign-in sent, from `ssoClaimValues()` |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 219 | `ssoRememberGroups` | **update** [auth_providers](/code/tables/auth_providers.md) (`oidc_groups_last_seen`) |  |
| 221 | `ssoRememberGroups` | **insert** [sso_groups_seen](/code/tables/sso_groups_seen.md) (`provider_id`, `group_name`, `first_seen_datetime`, `last_seen_datetime`) |  |

## Called from

- [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md)


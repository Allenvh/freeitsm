# ssoClaimsReady()

True once Database Verification has added the columns and tables.

```php
function ssoClaimsReady(PDO $conn): bool
```

Defined in [includes/sso_claims.php](/code/files/includes/sso_claims.php.md), line 37.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 42 | `ssoClaimsReady` | read [auth_providers](/code/tables/auth_providers.md) |  |
| 43 | `ssoClaimsReady` | read [sso_group_teams](/code/tables/sso_group_teams.md) |  |
| 44 | `ssoClaimsReady` | read [sso_groups_seen](/code/tables/sso_groups_seen.md) |  |

## Called from

- [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md)
- [api/system/get_sso_providers.php](/code/files/api/system/get_sso_providers.php.md)
- [api/system/save_sso_provider.php](/code/files/api/system/save_sso_provider.php.md)
- [includes/sso_claims.php](/code/files/includes/sso_claims.php.md) - in [ssoTeamMappings()](/code/functions/ssoTeamMappings.md), [ssoSeenGroups()](/code/functions/ssoSeenGroups.md), [ssoApplyGroupAccess()](/code/functions/ssoApplyGroupAccess.md), [ssoJitStartForTeams()](/code/functions/ssoJitStartForTeams.md)


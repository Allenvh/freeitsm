# ssoTeamMappings()

A provider's group -> team rows, with the team names.

```php
function ssoTeamMappings(PDO $conn, int $providerId): array
```

Defined in [includes/sso_claims.php](/code/files/includes/sso_claims.php.md), line 124.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$providerId` | `int` |  | the sign-in provider (auth_providers.id) |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 127 | `ssoTeamMappings` | read [sso_group_teams](/code/tables/sso_group_teams.md), read [teams](/code/tables/teams.md) |  |

## Calls

[ssoClaimsReady()](/code/functions/ssoClaimsReady.md)

## Called from

- [api/system/get_sso_providers.php](/code/files/api/system/get_sso_providers.php.md)
- [includes/sso_claims.php](/code/files/includes/sso_claims.php.md) - in [ssoApplyGroupAccess()](/code/functions/ssoApplyGroupAccess.md)


# ssoApplyGroupAccess()

At an analyst sign-in: admin from the admin group, team membership from the mappings. Returns what changed, for the log and the tests: ['admin' => true|false|null, 'added' => [team ids], 'removed' => [team ids]]

```php
function ssoApplyGroupAccess(PDO $conn, array $provider, int $analystId, array $groups): array
```

Defined in [includes/sso_claims.php](/code/files/includes/sso_claims.php.md), line 237.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$provider` | `array` |  | the auth_providers row (id, oidc_admin_group) |
| `$analystId` | `int` |  | the analyst signing in, whose teams and administrator flag are set |
| `$groups` | `array` |  | the group values the sign-in sent, from `ssoClaimValues()` |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 251 | `ssoApplyGroupAccess` | read [analyst_teams](/code/tables/analyst_teams.md) |  |
| 254 | `ssoApplyGroupAccess` | **insert** [analyst_teams](/code/tables/analyst_teams.md) (`analyst_id`, `team_id`, `created_datetime`) |  |
| 255 | `ssoApplyGroupAccess` | **delete** [analyst_teams](/code/tables/analyst_teams.md) |  |

## Calls

[analystSignInApplyMany()](/code/functions/analystSignInApplyMany.md), [analystSignInReady()](/code/functions/analystSignInReady.md), [ssoClaimsReady()](/code/functions/ssoClaimsReady.md), [ssoInGroup()](/code/functions/ssoInGroup.md), [ssoTeamMappings()](/code/functions/ssoTeamMappings.md)

## Called from

- [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md)


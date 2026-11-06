# ssoJitStartForTeams()

When a provider maps groups to teams, the analysts it CREATES get their modules from those teams - so, unless the provider names default modules, they start with no modules of their own (the account would otherwise have every module, and the teams could add nothing to that). Called by `oidcCreateAnalyst()` right after the insert.

```php
function ssoJitStartForTeams(
    PDO $conn,
    int $providerId,
    int $analystId,
    ?string $defaultModules
): void
```

Defined in [includes/sso_claims.php](/code/files/includes/sso_claims.php.md), line 295.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$providerId` | `int` |  | the sign-in provider (auth_providers.id) that created the analyst |
| `$analystId` | `int` |  | the analyst just created by the sign-in |
| `$defaultModules` | `?string` |  | the provider's default modules setting; anything non-blank leaves the analyst alone |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 298 | `ssoJitStartForTeams` | read [sso_group_teams](/code/tables/sso_group_teams.md) |  |
| 301 | `ssoJitStartForTeams` | **update** [analysts](/code/tables/analysts.md) (`can_access_all_modules`) |  |

## Calls

[ssoClaimsReady()](/code/functions/ssoClaimsReady.md)

## Called from

- [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md) - in [oidcCreateAnalyst()](/code/functions/oidcCreateAnalyst.md)


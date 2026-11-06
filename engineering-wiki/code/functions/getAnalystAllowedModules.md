# getAnalystAllowedModules()

Resolve an analyst's EFFECTIVE module access (issue #30) from every source: the analyst's own grant (all-modules flag, or analyst_modules rows) plus each team they belong to (the team's all-modules flag, or team_modules rows), combined per `getModulePermissionMode()`. Mirrors `getAccessibleTenantIds()` for companies.

Returns: null = ALL modules (unrestricted) · [] = NO modules · [keys] = a specific set.
Cached per request. System is deliberately excluded (gated by is_admin instead).

```php
function getAnalystAllowedModules($conn, $analyst_id)
```

Defined in [includes/functions.php](/code/files/includes/functions.php.md), line 119.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analyst_id` |  |  | the analyst whose access to resolve (cast to int); deactivated or unknown gives [] |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 135 | `getAnalystAllowedModules` | read [analysts](/code/tables/analysts.md) |  |
| 146 | `getAnalystAllowedModules` | read [analyst_modules](/code/tables/analyst_modules.md) |  |
| 155 | `getAnalystAllowedModules` | read [analyst_teams](/code/tables/analyst_teams.md), read [teams](/code/tables/teams.md) |  |
| 163 | `getAnalystAllowedModules` | read [team_modules](/code/tables/team_modules.md) |  |

## Calls

[getModulePermissionMode()](/code/functions/getModulePermissionMode.md)

## Called from

- [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md)
- [includes/functions.php](/code/files/includes/functions.php.md) - in [analystCanAccessModule()](/code/functions/analystCanAccessModule.md)
- [includes/services/analysts.php](/code/files/includes/services/analysts.php.md) - in `AnalystsService::access`
- [tasks/index.php](/code/files/tasks/index.php.md)


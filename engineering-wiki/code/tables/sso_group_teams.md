# sso_group_teams

SSO groups -> teams, and the groups seen at sign-in (3.5.0). includes/sso_claims.php.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `provider_id` | `INT NOT NULL` |  |
| `group_name` | `VARCHAR(255) NOT NULL` |  |
| `team_id` | `INT NOT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/sso_claims.php](/code/files/includes/sso_claims.php.md):155 | `ssoSaveTeamMappings` | delete |  |  |
| [includes/sso_claims.php](/code/files/includes/sso_claims.php.md):156 | `ssoSaveTeamMappings` | insert | `provider_id`, `group_name`, `team_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/sso_claims.php](/code/files/includes/sso_claims.php.md) | ssoClaimsReady L43, ssoTeamMappings L127, ssoJitStartForTeams L298 |  |


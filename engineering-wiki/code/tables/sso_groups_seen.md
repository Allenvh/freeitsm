# sso_groups_seen

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `provider_id` | `INT NOT NULL` |  |
| `group_name` | `VARCHAR(255) NOT NULL` |  |
| `first_seen_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `last_seen_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `label` | `VARCHAR(100) NULL` | the admin's name for the group |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/sso_claims.php](/code/files/includes/sso_claims.php.md):185 | `ssoSaveGroupLabels` | update | `label` |  |
| [includes/sso_claims.php](/code/files/includes/sso_claims.php.md):221 | `ssoRememberGroups` | insert | `provider_id`, `group_name`, `first_seen_datetime`, `last_seen_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/sso_claims.php](/code/files/includes/sso_claims.php.md) | ssoClaimsReady L44, ssoSeenGroups L169 |  |


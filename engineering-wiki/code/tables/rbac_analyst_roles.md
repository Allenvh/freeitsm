# rbac_analyst_roles

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `role_id` | `INT NOT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/role.php](/code/files/api/system/role.php.md):96 |  | delete |  |  |
| [api/system/role.php](/code/files/api/system/role.php.md):97 |  | insert | `analyst_id`, `role_id`, `created_datetime` |  |
| [includes/services/analysts.php](/code/files/includes/services/analysts.php.md):302 | `AnalystsService::writeManagedRole` | insert | `analyst_id`, `role_id`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/role.php](/code/files/api/system/role.php.md) | L35 |  |
| [api/system/roles.php](/code/files/api/system/roles.php.md) | L28 |  |
| [includes/rbac.php](/code/files/includes/rbac.php.md) | getAnalystCapabilities L63 |  |


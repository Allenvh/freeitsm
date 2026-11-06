# rbac_team_roles

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `team_id` | `INT NOT NULL` |  |
| `role_id` | `INT NOT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/role.php](/code/files/api/system/role.php.md):100 |  | delete |  |  |
| [api/system/role.php](/code/files/api/system/role.php.md):101 |  | insert | `team_id`, `role_id`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/role.php](/code/files/api/system/role.php.md) | L39 |  |
| [api/system/roles.php](/code/files/api/system/roles.php.md) | L28 |  |
| [includes/rbac.php](/code/files/includes/rbac.php.md) | getAnalystCapabilities L63 |  |


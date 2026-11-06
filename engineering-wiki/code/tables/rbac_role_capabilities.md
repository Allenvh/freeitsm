# rbac_role_capabilities

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `role_id` | `INT NOT NULL` |  |
| `capability_key` | `VARCHAR(100) NOT NULL` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/role.php](/code/files/api/system/role.php.md):92 |  | delete |  |  |
| [api/system/role.php](/code/files/api/system/role.php.md):93 |  | insert | `role_id`, `capability_key` |  |
| [includes/services/analysts.php](/code/files/includes/services/analysts.php.md):299 | `AnalystsService::writeManagedRole` | delete |  |  |
| [includes/services/analysts.php](/code/files/includes/services/analysts.php.md):300 | `AnalystsService::writeManagedRole` | insert | `role_id`, `capability_key` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/role.php](/code/files/api/system/role.php.md) | L31 |  |
| [api/system/roles.php](/code/files/api/system/roles.php.md) | L28 |  |
| [includes/feature_bingo/cards/system-core.php](/code/files/includes/feature_bingo/cards/system-core.php.md) | L169 |  |
| [includes/rbac.php](/code/files/includes/rbac.php.md) | getAnalystCapabilities L63 |  |
| [includes/services/analysts.php](/code/files/includes/services/analysts.php.md) | AnalystsService::access L163 |  |


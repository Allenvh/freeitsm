# team_modules

Per-team module grants (issue #30) — the team twin of analyst_modules, mirroring team_tenant_access. A row = "this team grants this module".

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `team_id` | `INT NOT NULL` |  |
| `module_key` | `VARCHAR(50) NOT NULL` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/save_module_grants.php](/code/files/api/system/save_module_grants.php.md):33 |  | delete |  |  |
| [api/system/save_module_grants.php](/code/files/api/system/save_module_grants.php.md):35 |  | insert | `team_id`, `module_key` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/get_effective_module_access.php](/code/files/api/system/get_effective_module_access.php.md) | L42 |  |
| [includes/functions.php](/code/files/includes/functions.php.md) | getAnalystAllowedModules L163 |  |


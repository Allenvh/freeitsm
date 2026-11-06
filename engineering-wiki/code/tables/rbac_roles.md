# rbac_roles

RBAC Layer 2 — per-module settings permissions (see docs/design/rbac.md). Deny by default; is_admin bypasses. Capability keys validated against the code registry in includes/rbac.php. Unique keys + FKs added below.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `description` | `VARCHAR(500) NULL` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_by_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |
| `managed_for_analyst_id` | `INT NULL` | 3.5.0 (PR #174): the analyst whose REST-API-managed role this is; NULL = an ordinary role. See includes/services/analysts.php. |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/role.php](/code/files/api/system/role.php.md):64 |  | delete |  |  |
| [api/system/role.php](/code/files/api/system/role.php.md):88 |  | update | `name`, `description`, `is_active`, `updated_datetime` |  |
| [api/system/roles.php](/code/files/api/system/roles.php.md):43 |  | insert | `name`, `description`, `is_active`, `created_by_id`, `created_datetime`, `updated_datetime` |  |
| [includes/services/analysts.php](/code/files/includes/services/analysts.php.md):289 | `AnalystsService::writeManagedRole` | insert | `name`, `description`, `is_active`, `created_by_id`, `managed_for_analyst_id`, `created_datetime`, `updated_datetime` |  |
| [includes/services/analysts.php](/code/files/includes/services/analysts.php.md):304 | `AnalystsService::writeManagedRole` | update | `updated_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/role.php](/code/files/api/system/role.php.md) | L26, L55 |  |
| [api/system/roles.php](/code/files/api/system/roles.php.md) | L25, L28 |  |
| [includes/feature_bingo/cards/system-core.php](/code/files/includes/feature_bingo/cards/system-core.php.md) | L169 |  |
| [includes/rbac.php](/code/files/includes/rbac.php.md) | getAnalystCapabilities L63 |  |
| [includes/services/analysts.php](/code/files/includes/services/analysts.php.md) | AnalystsService::managedRoleId L247 |  |


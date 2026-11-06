# rfp_conflicts

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `rfp_id` | `INT NOT NULL` |  |
| `consolidated_id_a` | `INT NOT NULL` |  |
| `consolidated_id_b` | `INT NOT NULL` |  |
| `ai_explanation` | `LONGTEXT NULL` |  |
| `resolution` | `VARCHAR(50) NOT NULL DEFAULT 'open'` |  |
| `resolution_notes` | `LONGTEXT NULL` |  |
| `resolved_by_analyst_id` | `INT NULL` |  |
| `resolved_datetime` | `DATETIME NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/rfp-builder/resolve_conflict.php](/code/files/api/rfp-builder/resolve_conflict.php.md):53 |  | update | `resolution`, `resolution_notes`, `resolved_by_analyst_id`, `resolved_datetime` |  |
| [api/rfp-builder/resolve_conflict.php](/code/files/api/rfp-builder/resolve_conflict.php.md):63 |  | update | `resolution`, `resolution_notes`, `resolved_by_analyst_id`, `resolved_datetime` |  |
| [api/rfp-builder/run_consolidation.php](/code/files/api/rfp-builder/run_consolidation.php.md):193 |  | insert | `rfp_id`, `consolidated_id_a`, `consolidated_id_b`, `ai_explanation`, `resolution` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/rfp-builder/get_consolidated.php](/code/files/api/rfp-builder/get_consolidated.php.md) | L87 |  |
| [api/rfp-builder/get_rfp.php](/code/files/api/rfp-builder/get_rfp.php.md) | L38 |  |
| [api/rfp-builder/resolve_conflict.php](/code/files/api/rfp-builder/resolve_conflict.php.md) | L45 |  |
| [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md) | rfpIdOf L98 |  |


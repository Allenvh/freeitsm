# change_field_sections

Layout tables that drive the Change form's section structure. Sections are user-editable (admin can add / rename / reorder / delete). Each field_key in change_field_layout corresponds to a fixed slot in the form (validated against a hardcoded catalogue in api/change-management/ get_field_layout.php) — what's configurable is which section the field appears in, the order within that section, and whether it's visible.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/change-management/save_field_layout.php](/code/files/api/change-management/save_field_layout.php.md):96 |  | delete |  |  |
| [api/change-management/save_field_layout.php](/code/files/api/change-management/save_field_layout.php.md):100 |  | delete |  |  |
| [api/change-management/save_field_layout.php](/code/files/api/change-management/save_field_layout.php.md):108 |  | update | `name`, `display_order` |  |
| [api/change-management/save_field_layout.php](/code/files/api/change-management/save_field_layout.php.md):111 |  | insert | `name`, `display_order` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):2630 |  | insert | `id`, `name`, `display_order` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/change-management/get_field_layout.php](/code/files/api/change-management/get_field_layout.php.md) | L37 |  |
| [api/change-management/save_field_layout.php](/code/files/api/change-management/save_field_layout.php.md) | L139, L173 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L2628 |  |
| [includes/feature_bingo/cards/changes.php](/code/files/includes/feature_bingo/cards/changes.php.md) | L88 |  |


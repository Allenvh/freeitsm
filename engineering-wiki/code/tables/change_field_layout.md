# change_field_layout

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `field_key` | `VARCHAR(50) NOT NULL` |  |
| `section_id` | `INT NOT NULL` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `is_visible` | `TINYINT(1) NOT NULL DEFAULT 1` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/change-management/save_field_layout.php](/code/files/api/change-management/save_field_layout.php.md):143 |  | insert | `field_key`, `section_id`, `display_order`, `is_visible` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):2679 |  | insert | `field_key`, `section_id`, `display_order`, `is_visible` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/change-management/get_field_layout.php](/code/files/api/change-management/get_field_layout.php.md) | L47 |  |
| [api/change-management/save_field_layout.php](/code/files/api/change-management/save_field_layout.php.md) | L182 |  |


# rfp_section_history

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `section_id` | `INT NOT NULL` |  |
| `version` | `INT NOT NULL` |  |
| `section_content` | `LONGTEXT NULL` |  |
| `is_manually_edited` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/rfp-builder/generate_section.php](/code/files/api/rfp-builder/generate_section.php.md):237 |  | insert | `section_id`, `version`, `section_content`, `is_manually_edited` |  |
| [api/rfp-builder/restore_section_version.php](/code/files/api/rfp-builder/restore_section_version.php.md):63 |  | insert | `section_id`, `version`, `section_content`, `is_manually_edited` |  |
| [api/rfp-builder/restyle_section.php](/code/files/api/rfp-builder/restyle_section.php.md):137 |  | insert | `section_id`, `version`, `section_content`, `is_manually_edited` |  |
| [api/rfp-builder/update_section.php](/code/files/api/rfp-builder/update_section.php.md):53 |  | insert | `section_id`, `version`, `section_content`, `is_manually_edited` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/rfp-builder/get_section_history.php](/code/files/api/rfp-builder/get_section_history.php.md) | L46 |  |
| [api/rfp-builder/restore_section_version.php](/code/files/api/rfp-builder/restore_section_version.php.md) | L49 |  |


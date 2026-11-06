# rfp_output_sections

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `rfp_id` | `INT NOT NULL` |  |
| `category_id` | `INT NOT NULL` |  |
| `section_title` | `VARCHAR(300) NOT NULL` |  |
| `section_content` | `LONGTEXT NULL` |  |
| `version` | `INT NOT NULL DEFAULT 1` |  |
| `is_manually_edited` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `requirements_hash` | `VARCHAR(64) NULL` |  |
| `generated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `edited_datetime` | `DATETIME NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/rfp-builder/generate_section.php](/code/files/api/rfp-builder/generate_section.php.md):250 |  | update | `section_content`, `version`, `is_manually_edited`, `requirements_hash`, `generated_datetime`, `edited_datetime` |  |
| [api/rfp-builder/generate_section.php](/code/files/api/rfp-builder/generate_section.php.md):263 |  | insert | `rfp_id`, `category_id`, `section_title`, `section_content`, `version`, `requirements_hash` |  |
| [api/rfp-builder/restore_section_version.php](/code/files/api/rfp-builder/restore_section_version.php.md):78 |  | update | `section_content`, `version`, `is_manually_edited`, `edited_datetime` |  |
| [api/rfp-builder/restyle_section.php](/code/files/api/rfp-builder/restyle_section.php.md):154 |  | update | `section_content`, `version`, `is_manually_edited`, `edited_datetime`, `generated_datetime` |  |
| [api/rfp-builder/update_section.php](/code/files/api/rfp-builder/update_section.php.md):68 |  | update | `section_content`, `version`, `is_manually_edited`, `edited_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/rfp-builder/generate_section.php](/code/files/api/rfp-builder/generate_section.php.md) | L120, L230 |  |
| [api/rfp-builder/get_processing_log.php](/code/files/api/rfp-builder/get_processing_log.php.md) | L61 |  |
| [api/rfp-builder/get_rfp.php](/code/files/api/rfp-builder/get_rfp.php.md) | L38 |  |
| [api/rfp-builder/get_section_history.php](/code/files/api/rfp-builder/get_section_history.php.md) | L32 |  |
| [api/rfp-builder/get_sections.php](/code/files/api/rfp-builder/get_sections.php.md) | L37 |  |
| [api/rfp-builder/restore_section_version.php](/code/files/api/rfp-builder/restore_section_version.php.md) | L41 |  |
| [api/rfp-builder/restyle_section.php](/code/files/api/rfp-builder/restyle_section.php.md) | L97 |  |
| [api/rfp-builder/update_section.php](/code/files/api/rfp-builder/update_section.php.md) | L39 |  |
| [contracts/rfp-builder/preview.php](/code/files/contracts/rfp-builder/preview.php.md) | L61 |  |
| [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md) | rfpIdOf L99 |  |


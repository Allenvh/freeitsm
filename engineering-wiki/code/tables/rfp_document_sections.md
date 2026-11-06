# rfp_document_sections

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `rfp_id` | `INT NOT NULL` |  |
| `section_key` | `VARCHAR(50) NOT NULL` |  |
| `section_title` | `VARCHAR(200) NOT NULL` |  |
| `section_content` | `LONGTEXT NULL` |  |
| `sort_order` | `INT NOT NULL DEFAULT 0` |  |
| `is_manually_edited` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `inputs_hash` | `VARCHAR(64) NULL` |  |
| `generated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `edited_datetime` | `DATETIME NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/rfp-builder/generate_framing.php](/code/files/api/rfp-builder/generate_framing.php.md):147 |  | update | `section_title`, `section_content`, `sort_order`, `is_manually_edited`, `inputs_hash`, `generated_datetime`, `edited_datetime` |  |
| [api/rfp-builder/generate_framing.php](/code/files/api/rfp-builder/generate_framing.php.md):161 |  | insert | `rfp_id`, `section_key`, `section_title`, `section_content`, `sort_order`, `inputs_hash` |  |
| [api/rfp-builder/restyle_framing.php](/code/files/api/rfp-builder/restyle_framing.php.md):127 |  | update | `section_content`, `is_manually_edited`, `edited_datetime`, `generated_datetime` |  |
| [api/rfp-builder/update_framing.php](/code/files/api/rfp-builder/update_framing.php.md):41 |  | update | `section_content`, `is_manually_edited`, `edited_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/rfp-builder/generate_framing.php](/code/files/api/rfp-builder/generate_framing.php.md) | L106 |  |
| [api/rfp-builder/get_sections.php](/code/files/api/rfp-builder/get_sections.php.md) | L83 |  |
| [api/rfp-builder/restyle_framing.php](/code/files/api/rfp-builder/restyle_framing.php.md) | L92 |  |
| [api/rfp-builder/update_framing.php](/code/files/api/rfp-builder/update_framing.php.md) | L35 |  |
| [contracts/rfp-builder/preview.php](/code/files/contracts/rfp-builder/preview.php.md) | L52 |  |
| [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md) | rfpIdOf L100 |  |


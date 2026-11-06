# rfp_categories

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `rfp_id` | `INT NOT NULL` |  |
| `name` | `VARCHAR(200) NOT NULL` |  |
| `description` | `LONGTEXT NULL` |  |
| `sort_order` | `INT NOT NULL DEFAULT 0` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/rfp-builder/run_consolidation.php](/code/files/api/rfp-builder/run_consolidation.php.md):134 |  | delete |  |  |
| [api/rfp-builder/run_consolidation.php](/code/files/api/rfp-builder/run_consolidation.php.md):138 |  | insert | `rfp_id`, `name`, `description`, `sort_order` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/rfp-builder/add_consolidated.php](/code/files/api/rfp-builder/add_consolidated.php.md) | L58 |  |
| [api/rfp-builder/generate_section.php](/code/files/api/rfp-builder/generate_section.php.md) | L139 |  |
| [api/rfp-builder/get_compare_data.php](/code/files/api/rfp-builder/get_compare_data.php.md) | L41 |  |
| [api/rfp-builder/get_consolidated.php](/code/files/api/rfp-builder/get_consolidated.php.md) | L37 |  |
| [api/rfp-builder/get_coverage.php](/code/files/api/rfp-builder/get_coverage.php.md) | L38 |  |
| [api/rfp-builder/get_rfp.php](/code/files/api/rfp-builder/get_rfp.php.md) | L38 |  |
| [api/rfp-builder/get_scores.php](/code/files/api/rfp-builder/get_scores.php.md) | L52 |  |
| [api/rfp-builder/get_section_history.php](/code/files/api/rfp-builder/get_section_history.php.md) | L32 |  |
| [api/rfp-builder/get_sections.php](/code/files/api/rfp-builder/get_sections.php.md) | L37 |  |
| [api/rfp-builder/merge_consolidated.php](/code/files/api/rfp-builder/merge_consolidated.php.md) | L77 |  |
| [api/rfp-builder/restyle_section.php](/code/files/api/rfp-builder/restyle_section.php.md) | L97 |  |
| [api/rfp-builder/split_consolidated.php](/code/files/api/rfp-builder/split_consolidated.php.md) | L81 |  |
| [api/rfp-builder/update_consolidated.php](/code/files/api/rfp-builder/update_consolidated.php.md) | L56 |  |
| [contracts/rfp-builder/preview.php](/code/files/contracts/rfp-builder/preview.php.md) | L61 |  |
| [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md) | rfpAiGenerateSectionStreaming L1012, rfpAiGenerateFramingStreaming L1256 |  |


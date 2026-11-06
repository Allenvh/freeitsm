# rfp_consolidated_sources

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `consolidated_id` | `INT NOT NULL` |  |
| `extracted_id` | `INT NOT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/rfp-builder/add_consolidated.php](/code/files/api/rfp-builder/add_consolidated.php.md):88 |  | insert | `consolidated_id`, `extracted_id` |  |
| [api/rfp-builder/merge_consolidated.php](/code/files/api/rfp-builder/merge_consolidated.php.md):101 |  | insert | `consolidated_id`, `extracted_id` |  |
| [api/rfp-builder/run_consolidation.php](/code/files/api/rfp-builder/run_consolidation.php.md):157 |  | insert | `consolidated_id`, `extracted_id` |  |
| [api/rfp-builder/split_consolidated.php](/code/files/api/rfp-builder/split_consolidated.php.md):116 |  | insert | `consolidated_id`, `extracted_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/rfp-builder/generate_section.php](/code/files/api/rfp-builder/generate_section.php.md) | L164 |  |
| [api/rfp-builder/get_consolidated.php](/code/files/api/rfp-builder/get_consolidated.php.md) | L63 |  |
| [api/rfp-builder/get_coverage.php](/code/files/api/rfp-builder/get_coverage.php.md) | L63, L92, L109 |  |
| [api/rfp-builder/merge_consolidated.php](/code/files/api/rfp-builder/merge_consolidated.php.md) | L84 |  |
| [api/rfp-builder/split_consolidated.php](/code/files/api/rfp-builder/split_consolidated.php.md) | L61 |  |
| [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md) | rfpAiGenerateSectionStreaming L1037 |  |


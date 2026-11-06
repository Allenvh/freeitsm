# rfp_consolidated_requirements

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `rfp_id` | `INT NOT NULL` |  |
| `category_id` | `INT NULL` |  |
| `requirement_text` | `LONGTEXT NOT NULL` |  |
| `requirement_type` | `VARCHAR(50) NOT NULL DEFAULT 'requirement'` |  |
| `priority` | `VARCHAR(20) NOT NULL DEFAULT 'medium'` |  |
| `ai_rationale` | `LONGTEXT NULL` |  |
| `is_locked` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/rfp-builder/add_consolidated.php](/code/files/api/rfp-builder/add_consolidated.php.md):79 |  | insert | `rfp_id`, `category_id`, `requirement_text`, `requirement_type`, `priority`, `ai_rationale` |  |
| [api/rfp-builder/delete_consolidated.php](/code/files/api/rfp-builder/delete_consolidated.php.md):37 |  | delete |  |  |
| [api/rfp-builder/lock_consolidation.php](/code/files/api/rfp-builder/lock_consolidation.php.md):54 |  | update | `is_locked`, `updated_datetime` |  |
| [api/rfp-builder/merge_consolidated.php](/code/files/api/rfp-builder/merge_consolidated.php.md):92 |  | insert | `rfp_id`, `category_id`, `requirement_text`, `requirement_type`, `priority`, `ai_rationale` |  |
| [api/rfp-builder/merge_consolidated.php](/code/files/api/rfp-builder/merge_consolidated.php.md):111 |  | delete |  |  |
| [api/rfp-builder/run_consolidation.php](/code/files/api/rfp-builder/run_consolidation.php.md):132 |  | delete |  |  |
| [api/rfp-builder/run_consolidation.php](/code/files/api/rfp-builder/run_consolidation.php.md):152 |  | insert | `rfp_id`, `category_id`, `requirement_text`, `requirement_type`, `priority`, `ai_rationale` |  |
| [api/rfp-builder/split_consolidated.php](/code/files/api/rfp-builder/split_consolidated.php.md):111 |  | insert | `rfp_id`, `category_id`, `requirement_text`, `requirement_type`, `priority`, `ai_rationale` |  |
| [api/rfp-builder/split_consolidated.php](/code/files/api/rfp-builder/split_consolidated.php.md):131 |  | delete |  |  |
| [api/rfp-builder/update_consolidated.php](/code/files/api/rfp-builder/update_consolidated.php.md):62 |  | update | `requirement_text`, `requirement_type`, `priority`, `category_id`, `ai_rationale`, `updated_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/rfp-builder/delete_consolidated.php](/code/files/api/rfp-builder/delete_consolidated.php.md) | L32 |  |
| [api/rfp-builder/generate_framing.php](/code/files/api/rfp-builder/generate_framing.php.md) | L86 |  |
| [api/rfp-builder/generate_section.php](/code/files/api/rfp-builder/generate_section.php.md) | L98, L149 |  |
| [api/rfp-builder/get_compare_data.php](/code/files/api/rfp-builder/get_compare_data.php.md) | L52 |  |
| [api/rfp-builder/get_consolidated.php](/code/files/api/rfp-builder/get_consolidated.php.md) | L46, L87 |  |
| [api/rfp-builder/get_coverage.php](/code/files/api/rfp-builder/get_coverage.php.md) | L63, L92, L109, L136 |  |
| [api/rfp-builder/get_rfp.php](/code/files/api/rfp-builder/get_rfp.php.md) | L38 |  |
| [api/rfp-builder/get_rfps.php](/code/files/api/rfp-builder/get_rfps.php.md) | L35 |  |
| [api/rfp-builder/get_scores.php](/code/files/api/rfp-builder/get_scores.php.md) | L59 |  |
| [api/rfp-builder/get_sections.php](/code/files/api/rfp-builder/get_sections.php.md) | L37, L71 |  |
| [api/rfp-builder/lock_consolidation.php](/code/files/api/rfp-builder/lock_consolidation.php.md) | L44 |  |
| [api/rfp-builder/merge_consolidated.php](/code/files/api/rfp-builder/merge_consolidated.php.md) | L63 |  |
| [api/rfp-builder/restyle_framing.php](/code/files/api/rfp-builder/restyle_framing.php.md) | L78 |  |
| [api/rfp-builder/restyle_section.php](/code/files/api/rfp-builder/restyle_section.php.md) | L83 |  |
| [api/rfp-builder/save_score.php](/code/files/api/rfp-builder/save_score.php.md) | L55 |  |
| [api/rfp-builder/split_consolidated.php](/code/files/api/rfp-builder/split_consolidated.php.md) | L52 |  |
| [api/rfp-builder/update_consolidated.php](/code/files/api/rfp-builder/update_consolidated.php.md) | L50 |  |
| [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md) | rfpAiGenerateSectionStreaming L1021, rfpAiGenerateFramingStreaming L1256 |  |
| [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md) | rfpIdOf L97 |  |


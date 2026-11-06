# rfp_extracted_requirements

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `rfp_id` | `INT NOT NULL` |  |
| `document_id` | `INT NOT NULL` |  |
| `requirement_text` | `LONGTEXT NOT NULL` |  |
| `requirement_type` | `VARCHAR(50) NOT NULL DEFAULT 'requirement'` |  |
| `source_quote` | `LONGTEXT NULL` |  |
| `ai_confidence` | `DECIMAL(3,2) NULL` |  |
| `is_consolidated` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/rfp-builder/delete_extracted.php](/code/files/api/rfp-builder/delete_extracted.php.md):35 |  | delete |  |  |
| [api/rfp-builder/extract_requirements.php](/code/files/api/rfp-builder/extract_requirements.php.md):66 |  | delete |  |  |
| [api/rfp-builder/extract_requirements.php](/code/files/api/rfp-builder/extract_requirements.php.md):72 |  | insert | `rfp_id`, `document_id`, `requirement_text`, `requirement_type`, `source_quote`, `ai_confidence` |  |
| [api/rfp-builder/save_extracted.php](/code/files/api/rfp-builder/save_extracted.php.md):51 |  | update | `requirement_text`, `requirement_type`, `source_quote`, `updated_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/rfp-builder/add_consolidated.php](/code/files/api/rfp-builder/add_consolidated.php.md) | L68 |  |
| [api/rfp-builder/delete_extracted.php](/code/files/api/rfp-builder/delete_extracted.php.md) | L30 |  |
| [api/rfp-builder/generate_section.php](/code/files/api/rfp-builder/generate_section.php.md) | L164 |  |
| [api/rfp-builder/get_consolidated.php](/code/files/api/rfp-builder/get_consolidated.php.md) | L63 |  |
| [api/rfp-builder/get_coverage.php](/code/files/api/rfp-builder/get_coverage.php.md) | L49, L63, L92, L109 |  |
| [api/rfp-builder/get_documents.php](/code/files/api/rfp-builder/get_documents.php.md) | L33 |  |
| [api/rfp-builder/get_extracted.php](/code/files/api/rfp-builder/get_extracted.php.md) | L43, L79 |  |
| [api/rfp-builder/get_rfp.php](/code/files/api/rfp-builder/get_rfp.php.md) | L38 |  |
| [api/rfp-builder/run_consolidation.php](/code/files/api/rfp-builder/run_consolidation.php.md) | L90 |  |
| [api/rfp-builder/save_extracted.php](/code/files/api/rfp-builder/save_extracted.php.md) | L45 |  |
| [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md) | rfpAiGenerateSectionStreaming L1037, rfpAiGenerateFramingStreaming L1272 |  |
| [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md) | rfpIdOf L96 |  |


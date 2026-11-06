# rfp_documents

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `rfp_id` | `INT NOT NULL` |  |
| `department_id` | `INT NULL` |  |
| `filename` | `VARCHAR(255) NOT NULL` |  |
| `original_filename` | `VARCHAR(255) NOT NULL` |  |
| `file_path` | `VARCHAR(500) NOT NULL` |  |
| `raw_text` | `LONGTEXT NULL` |  |
| `status` | `VARCHAR(50) NOT NULL DEFAULT 'uploaded'` |  |
| `uploaded_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/rfp-builder/delete_document.php](/code/files/api/rfp-builder/delete_document.php.md):40 |  | delete |  |  |
| [api/rfp-builder/extract_document.php](/code/files/api/rfp-builder/extract_document.php.md):46 |  | update | `status`, `updated_datetime` |  |
| [api/rfp-builder/extract_document.php](/code/files/api/rfp-builder/extract_document.php.md):55 |  | update | `raw_text`, `status`, `updated_datetime` |  |
| [api/rfp-builder/extract_document.php](/code/files/api/rfp-builder/extract_document.php.md):75 |  | update | `status`, `updated_datetime` |  |
| [api/rfp-builder/extract_requirements.php](/code/files/api/rfp-builder/extract_requirements.php.md):97 |  | update | `status`, `updated_datetime` |  |
| [api/rfp-builder/upload_document.php](/code/files/api/rfp-builder/upload_document.php.md):98 |  | insert | `rfp_id`, `department_id`, `filename`, `original_filename`, `file_path`, `status` |  |
| [api/rfp-builder/upload_document.php](/code/files/api/rfp-builder/upload_document.php.md):112 |  | update | `raw_text`, `status`, `updated_datetime` |  |
| [api/rfp-builder/upload_document.php](/code/files/api/rfp-builder/upload_document.php.md):127 |  | update | `status`, `updated_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/rfp-builder/delete_document.php](/code/files/api/rfp-builder/delete_document.php.md) | L33 |  |
| [api/rfp-builder/delete_rfp.php](/code/files/api/rfp-builder/delete_rfp.php.md) | L41 |  |
| [api/rfp-builder/extract_document.php](/code/files/api/rfp-builder/extract_document.php.md) | L37 |  |
| [api/rfp-builder/extract_requirements.php](/code/files/api/rfp-builder/extract_requirements.php.md) | L44 |  |
| [api/rfp-builder/generate_section.php](/code/files/api/rfp-builder/generate_section.php.md) | L164 |  |
| [api/rfp-builder/get_consolidated.php](/code/files/api/rfp-builder/get_consolidated.php.md) | L63 |  |
| [api/rfp-builder/get_coverage.php](/code/files/api/rfp-builder/get_coverage.php.md) | L49, L63, L92, L109 |  |
| [api/rfp-builder/get_document_text.php](/code/files/api/rfp-builder/get_document_text.php.md) | L31 |  |
| [api/rfp-builder/get_documents.php](/code/files/api/rfp-builder/get_documents.php.md) | L33 |  |
| [api/rfp-builder/get_extracted.php](/code/files/api/rfp-builder/get_extracted.php.md) | L43, L94 |  |
| [api/rfp-builder/get_processing_log.php](/code/files/api/rfp-builder/get_processing_log.php.md) | L61 |  |
| [api/rfp-builder/get_rfp.php](/code/files/api/rfp-builder/get_rfp.php.md) | L38 |  |
| [api/rfp-builder/get_rfps.php](/code/files/api/rfp-builder/get_rfps.php.md) | L35 |  |
| [api/rfp-builder/run_consolidation.php](/code/files/api/rfp-builder/run_consolidation.php.md) | L90 |  |
| [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md) | rfpAiGenerateSectionStreaming L1037, rfpAiGenerateFramingStreaming L1272 |  |
| [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md) | rfpIdOf L95 |  |


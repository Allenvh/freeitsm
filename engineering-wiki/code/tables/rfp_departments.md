# rfp_departments

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `colour` | `VARCHAR(7) NOT NULL DEFAULT '#6c757d'` |  |
| `sort_order` | `INT NOT NULL DEFAULT 0` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/rfp-builder/delete_rfp_department.php](/code/files/api/rfp-builder/delete_rfp_department.php.md):32 |  | delete |  |  |
| [api/rfp-builder/save_rfp_department.php](/code/files/api/rfp-builder/save_rfp_department.php.md):41 |  | update | `name`, `colour`, `sort_order`, `is_active` |  |
| [api/rfp-builder/save_rfp_department.php](/code/files/api/rfp-builder/save_rfp_department.php.md):47 |  | insert | `name`, `colour`, `sort_order`, `is_active` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/rfp-builder/extract_requirements.php](/code/files/api/rfp-builder/extract_requirements.php.md) | L44 |  |
| [api/rfp-builder/generate_section.php](/code/files/api/rfp-builder/generate_section.php.md) | L164 |  |
| [api/rfp-builder/get_consolidated.php](/code/files/api/rfp-builder/get_consolidated.php.md) | L63 |  |
| [api/rfp-builder/get_coverage.php](/code/files/api/rfp-builder/get_coverage.php.md) | L49, L63, L92, L109 |  |
| [api/rfp-builder/get_documents.php](/code/files/api/rfp-builder/get_documents.php.md) | L33 |  |
| [api/rfp-builder/get_extracted.php](/code/files/api/rfp-builder/get_extracted.php.md) | L43, L94 |  |
| [api/rfp-builder/get_rfp_departments.php](/code/files/api/rfp-builder/get_rfp_departments.php.md) | L23 |  |
| [api/rfp-builder/run_consolidation.php](/code/files/api/rfp-builder/run_consolidation.php.md) | L90 |  |
| [api/rfp-builder/upload_document.php](/code/files/api/rfp-builder/upload_document.php.md) | L73 |  |
| [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md) | rfpAiGenerateSectionStreaming L1037, rfpAiGenerateFramingStreaming L1272 |  |


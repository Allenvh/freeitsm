# rfp_processing_log

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `rfp_id` | `INT NOT NULL` |  |
| `document_id` | `INT NULL` |  |
| `section_id` | `INT NULL` |  |
| `action` | `VARCHAR(100) NOT NULL` |  |
| `status` | `VARCHAR(50) NOT NULL` |  |
| `details` | `LONGTEXT NULL` |  |
| `tokens_in` | `INT NULL` |  |
| `tokens_out` | `INT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md):506 | `rfpAiLogAction` | insert | `rfp_id`, `document_id`, `section_id`, `action`, `status`, `details`, `tokens_in`, `tokens_out` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/rfp-builder/get_processing_log.php](/code/files/api/rfp-builder/get_processing_log.php.md) | L47, L61 |  |


# api/rfp-builder/upload_document.php

> API endpoint - 140 lines

Upload a .docx requirements document for an RFP. Saves the file to disk under contracts/rfp-builder/uploads/, inserts a row in rfp_documents, and runs DOCX text extraction inline.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/upload_document.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |
| Reads | `POST rfp_id`, `POST department_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 65 |  | read [rfps](/code/tables/rfps.md) |  |
| 73 |  | read [rfp_departments](/code/tables/rfp_departments.md) |  |
| 98 |  | **insert** [rfp_documents](/code/tables/rfp_documents.md) (`rfp_id`, `department_id`, `filename`, `original_filename`, `file_path`, `status`) |  |
| 105 |  | **update** [rfps](/code/tables/rfps.md) (`updated_datetime`) |  |
| 112 |  | **update** [rfp_documents](/code/tables/rfp_documents.md) (`raw_text`, `status`, `updated_datetime`) |  |
| 127 |  | **update** [rfp_documents](/code/tables/rfp_documents.md) (`status`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_docx_parser.php](/code/files/includes/rfp_docx_parser.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpExtractDocxText()](/code/functions/rfpExtractDocxText.md), [rfpGate()](/code/functions/rfpGate.md)


# api/rfp-builder/extract_document.php

> API endpoint - 81 lines

Re-run DOCX text extraction on the on-disk file for a document. Useful when initial extraction failed (status = 'error') or after the file has been replaced.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/extract_document.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 37 |  | read [rfp_documents](/code/tables/rfp_documents.md) |  |
| 46 |  | **update** [rfp_documents](/code/tables/rfp_documents.md) (`status`, `updated_datetime`) |  |
| 55 |  | **update** [rfp_documents](/code/tables/rfp_documents.md) (`raw_text`, `status`, `updated_datetime`) |  |
| 61 |  | **update** [rfps](/code/tables/rfps.md) (`updated_datetime`) |  |
| 75 |  | **update** [rfp_documents](/code/tables/rfp_documents.md) (`status`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_docx_parser.php](/code/files/includes/rfp_docx_parser.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpExtractDocxText()](/code/functions/rfpExtractDocxText.md), [rfpGate()](/code/functions/rfpGate.md)


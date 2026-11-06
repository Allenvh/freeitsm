# api/rfp-builder/extract_requirements.php

> API endpoint - 119 lines

Run Pass 1 (AI extraction) on a single uploaded document.

Wipes any previous extracted_requirements rows for the document,
calls the AI helper, inserts the parsed items, and flips the
document status to "processed". On failure, the document is left
in its previous state (raw_text untouched) and the failure is
logged to rfp_processing_log.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/extract_requirements.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 |  | read [rfp_documents](/code/tables/rfp_documents.md), read [rfp_departments](/code/tables/rfp_departments.md) |  |
| 66 |  | **delete** [rfp_extracted_requirements](/code/tables/rfp_extracted_requirements.md) |  |
| 72 |  | **insert** [rfp_extracted_requirements](/code/tables/rfp_extracted_requirements.md) (`rfp_id`, `document_id`, `requirement_text`, `requirement_type`, `source_quote`, `ai_confidence`) |  |
| 97 |  | **update** [rfp_documents](/code/tables/rfp_documents.md) (`status`, `updated_datetime`) |  |
| 103 |  | **update** [rfps](/code/tables/rfps.md) (`updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpAiExtractRequirements()](/code/functions/rfpAiExtractRequirements.md), [rfpGate()](/code/functions/rfpGate.md)


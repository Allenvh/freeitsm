# api/rfp-builder/get_extracted.php

> API endpoint - 112 lines

List extracted requirements for an RFP, with optional filters. Returns requirement_text, source_quote, department + document metadata, type, ai_confidence — everything the browser page needs to render.

Query params:
  rfp_id           required
  department_id    optional ("" or 0 = all)
  requirement_type optional (requirement | pain_point | challenge | "" for all)

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/get_extracted.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |
| Reads | `GET rfp_id`, `GET department_id`, `GET requirement_type` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 43 |  | read [rfp_extracted_requirements](/code/tables/rfp_extracted_requirements.md), read [rfp_documents](/code/tables/rfp_documents.md), read [rfp_departments](/code/tables/rfp_departments.md) |  |
| 79 |  | read [rfp_extracted_requirements](/code/tables/rfp_extracted_requirements.md) |  |
| 94 |  | read [rfp_documents](/code/tables/rfp_documents.md), read [rfp_departments](/code/tables/rfp_departments.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)


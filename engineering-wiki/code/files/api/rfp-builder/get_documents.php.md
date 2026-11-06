# api/rfp-builder/get_documents.php

> API endpoint - 57 lines

List documents for an RFP, with department info and a character count. raw_text itself is NOT returned — fetch via get_document_text.php.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/get_documents.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |
| Reads | `GET rfp_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 33 |  | read [rfp_extracted_requirements](/code/tables/rfp_extracted_requirements.md), read [rfp_documents](/code/tables/rfp_documents.md), read [rfp_departments](/code/tables/rfp_departments.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)


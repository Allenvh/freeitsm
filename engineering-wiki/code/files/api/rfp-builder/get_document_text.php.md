# api/rfp-builder/get_document_text.php

> API endpoint - 57 lines

Return a single document's extracted plain text plus word/char counts.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/get_document_text.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |
| Reads | `GET id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 |  | read [rfp_documents](/code/tables/rfp_documents.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)


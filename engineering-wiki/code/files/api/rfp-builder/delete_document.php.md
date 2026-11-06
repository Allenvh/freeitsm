# api/rfp-builder/delete_document.php

> API endpoint - 54 lines

Delete a document. The on-disk file is removed too. Cascade to rfp_extracted_requirements / rfp_consolidated_sources is handled by FK ON DELETE CASCADE.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/delete_document.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 33 |  | read [rfp_documents](/code/tables/rfp_documents.md) |  |
| 40 |  | **delete** [rfp_documents](/code/tables/rfp_documents.md) |  |
| 47 |  | **update** [rfps](/code/tables/rfps.md) (`updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)


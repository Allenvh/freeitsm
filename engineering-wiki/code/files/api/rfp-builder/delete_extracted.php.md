# api/rfp-builder/delete_extracted.php

> API endpoint - 45 lines

Delete a single extracted requirement. Cascade to rfp_consolidated_sources is handled by FK ON DELETE CASCADE.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/delete_extracted.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 30 |  | read [rfp_extracted_requirements](/code/tables/rfp_extracted_requirements.md) |  |
| 35 |  | **delete** [rfp_extracted_requirements](/code/tables/rfp_extracted_requirements.md) |  |
| 38 |  | **update** [rfps](/code/tables/rfps.md) (`updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)


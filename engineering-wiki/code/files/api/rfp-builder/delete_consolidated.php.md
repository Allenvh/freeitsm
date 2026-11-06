# api/rfp-builder/delete_consolidated.php

> API endpoint - 47 lines

Delete a single consolidated requirement. The M:N source links and any conflicts referencing this row are cleaned up automatically by ON DELETE CASCADE.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/delete_consolidated.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 32 |  | read [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) |  |
| 37 |  | **delete** [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) |  |
| 40 |  | **update** [rfps](/code/tables/rfps.md) (`updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)


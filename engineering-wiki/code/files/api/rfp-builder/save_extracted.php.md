# api/rfp-builder/save_extracted.php

> API endpoint - 65 lines

Update one extracted requirement row (text, type, source_quote). Triggered from the inline edit modal on the extracted-requirements page.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/save_extracted.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 45 |  | read [rfp_extracted_requirements](/code/tables/rfp_extracted_requirements.md) |  |
| 51 |  | **update** [rfp_extracted_requirements](/code/tables/rfp_extracted_requirements.md) (`requirement_text`, `requirement_type`, `source_quote`, `updated_datetime`) |  |
| 58 |  | **update** [rfps](/code/tables/rfps.md) (`updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)


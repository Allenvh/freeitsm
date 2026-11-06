# api/rfp-builder/get_section_history.php

> API endpoint - 67 lines

Return the version history for a single category section, plus the current row's metadata so the UI can show "current" alongside the historical entries. Newest first.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/get_section_history.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |
| Reads | `GET section_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 32 |  | read [rfp_output_sections](/code/tables/rfp_output_sections.md), read [rfp_categories](/code/tables/rfp_categories.md) |  |
| 46 |  | read [rfp_section_history](/code/tables/rfp_section_history.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)


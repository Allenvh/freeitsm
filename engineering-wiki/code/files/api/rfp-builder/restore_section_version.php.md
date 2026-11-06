# api/rfp-builder/restore_section_version.php

> API endpoint - 105 lines

Restore a previous version of a category section from rfp_section_history back into rfp_output_sections. Snapshots the current version first so the restore itself is reversible — every transition lives in the history table.

Restore counts as a manual action, so the resulting row is marked
is_manually_edited = 1 (the user has explicitly chosen this content
over what the AI most recently produced).

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/restore_section_version.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 41 |  | read [rfp_output_sections](/code/tables/rfp_output_sections.md) |  |
| 49 |  | read [rfp_section_history](/code/tables/rfp_section_history.md) |  |
| 63 |  | **insert** [rfp_section_history](/code/tables/rfp_section_history.md) (`section_id`, `version`, `section_content`, `is_manually_edited`) |  |
| 78 |  | **update** [rfp_output_sections](/code/tables/rfp_output_sections.md) (`section_content`, `version`, `is_manually_edited`, `edited_datetime`) |  |
| 87 |  | **update** [rfps](/code/tables/rfps.md) (`updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)


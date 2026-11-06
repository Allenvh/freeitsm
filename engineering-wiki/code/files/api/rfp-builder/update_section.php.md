# api/rfp-builder/update_section.php

> API endpoint - 90 lines

Manually edit a category section's HTML. Snapshots the current version into rfp_section_history before overwriting, so the version history sidebar always shows every state the section has ever been in. Marks is_manually_edited = 1 so future hash-skip on Generate-all leaves the edit alone (only an explicit Re-generate regenerates a manually-edited section).

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/update_section.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 39 |  | read [rfp_output_sections](/code/tables/rfp_output_sections.md) |  |
| 53 |  | **insert** [rfp_section_history](/code/tables/rfp_section_history.md) (`section_id`, `version`, `section_content`, `is_manually_edited`) |  |
| 68 |  | **update** [rfp_output_sections](/code/tables/rfp_output_sections.md) (`section_content`, `version`, `is_manually_edited`, `edited_datetime`) |  |
| 77 |  | **update** [rfps](/code/tables/rfps.md) (`updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)


# api/rfp-builder/update_framing.php

> API endpoint - 56 lines

Manually edit a framing section's content — the analyst can hand-tune what the AI drafted. Marks the row is_manually_edited so future regenerations are gated on `force=1` (we don't trample edits silently).

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/update_framing.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 35 |  | read [rfp_document_sections](/code/tables/rfp_document_sections.md) |  |
| 41 |  | **update** [rfp_document_sections](/code/tables/rfp_document_sections.md) (`section_content`, `is_manually_edited`, `edited_datetime`) |  |
| 49 |  | **update** [rfps](/code/tables/rfps.md) (`updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)


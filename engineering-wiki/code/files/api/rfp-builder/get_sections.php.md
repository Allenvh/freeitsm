# api/rfp-builder/get_sections.php

> API endpoint - 117 lines

Return the full section tree for an RFP — every category with its generated section content (or null if not yet generated). Powers the document.php page in 4a; same shape will work for the full preview + PDF export in 4d.

Includes the lock state of the RFP's consolidated requirements so
the page can show the right call-to-action — generation is gated
on a fully-locked consolidation set.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/get_sections.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |
| Reads | `GET rfp_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 37 |  | read [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md), read [rfp_categories](/code/tables/rfp_categories.md), read [rfp_output_sections](/code/tables/rfp_output_sections.md) |  |
| 71 |  | read [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) |  |
| 83 |  | read [rfp_document_sections](/code/tables/rfp_document_sections.md) |  |
| 97 |  | read [rfps](/code/tables/rfps.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)


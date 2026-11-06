# contracts/rfp-builder/preview.php

> PHP - 322 lines

RFP Builder — single-page document preview. Renders the full document — framing sections (introduction / scope / response_instructions) followed by per-category sections — as a clean print-friendly document. No editing UI. PDF export is browser print (Ctrl+P / Cmd+P) for now; Phase 4d will add a dedicated export button and any final styling polish.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 42 |  | read [rfps](/code/tables/rfps.md) |  |
| 52 |  | read [rfp_document_sections](/code/tables/rfp_document_sections.md) |  |
| 61 |  | read [rfp_categories](/code/tables/rfp_categories.md), read [rfp_output_sections](/code/tables/rfp_output_sections.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [rfpCanView()](/code/functions/rfpCanView.md), [t()](/code/functions/t.md)


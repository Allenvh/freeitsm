# contracts/rfp-builder/document.php

> PHP - 1292 lines

RFP Builder — generated document page (Phase 4 step 4a).

Lists every category from the consolidation pass with the AI-
generated section content underneath (or a "not yet generated"
placeholder). "Generate all" fires N sequential SSE calls (one per
category), each with the streaming-modal pattern from Phase 3a but
extended to a checklist of categories with per-row state.
Per-section "Generate" / "Re-generate" buttons handle one category
at a time. TinyMCE editing + version history land in 4b; preview
and PDF export land in 4d.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [contracts/includes/header.php](/code/files/contracts/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/tinymce/tinymce.min.js](/code/files/assets/js/tinymce/tinymce.min.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)


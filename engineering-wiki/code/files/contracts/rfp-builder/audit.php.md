# contracts/rfp-builder/audit.php

> PHP - 400 lines

RFP Builder — full AI audit trail (Phase 6 step 6b).

Surfaces the entire rfp_processing_log for one RFP in a filterable
table — every AI call ever made for this RFP, with action, status,
target document/section, tokens (in/out/cached), call duration,
and the JSON details payload expanded for inspection. Companion
to the AI activity panel on the overview, which only shows the
last 25 entries.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [contracts/includes/header.php](/code/files/contracts/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)


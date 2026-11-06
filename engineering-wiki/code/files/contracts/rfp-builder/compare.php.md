# contracts/rfp-builder/compare.php

> PHP - 515 lines

RFP Builder — cross-supplier compare (Phase 6 step 6a).

Side-by-side decision-making view: big-number cards ranked by
overall score, multi-supplier radar overlay, and a category
winners table that highlights the leading supplier per category
with the gap to second place.

Read-only by design — scoring still happens on scoring.php.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [contracts/includes/header.php](/code/files/contracts/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/chart.min.js](/code/files/assets/js/chart.min.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)


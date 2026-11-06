# contracts/rfp-builder/suppliers.php

> PHP - 553 lines

RFP Builder — invited suppliers (Phase 5 step 5a). Lists every supplier invited to evaluate this RFP, with a picker over the existing suppliers table for adding more, and an inline "create prospective" form for suppliers not yet in FreeITSM. Demo dates and notes editable per row. Removing an invitation also clears any submitted scores for that supplier (the scoring page lands in 5b).

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [contracts/includes/header.php](/code/files/contracts/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)


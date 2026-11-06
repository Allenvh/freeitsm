# contracts/rfp-builder/coverage.php

> PHP - 388 lines

RFP Builder — coverage heatmap (Phase 5 step 5d).

Visualises which departments contributed requirements to which
categories. Useful for spotting:
  - single-source categories (only one dept asked = potential bias)
  - multi-dept consensus categories (many depts asked = strong signal)
  - departmental specialisations (which depts hit many categories)

Heat intensity scales linearly between 0 (no contribution) and the
row's max count (so each category compares against itself, not
across the whole matrix — keeps the picture readable).

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [contracts/includes/header.php](/code/files/contracts/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)


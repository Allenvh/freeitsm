# contracts/rfp-builder/scoring.php

> PHP - 872 lines

RFP Builder — supplier scoring (Phase 5 step 5b).

Score one supplier at a time against every consolidated requirement
with click-to-light score boxes (0–5, red-to-green colour gradient)
and a generously-sized notes field below each. Left sidebar carries
the supplier picker and a live "Score by category" panel that
updates as you score. Sticky bottom bar shows the running totals
plus a spider-web button that opens a full-screen radar chart of
the per-category averages.

All scores autosave on change. Multi-analyst rollup is single-blind
(other analysts' counts and averages, never names or individual
scores).

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [contracts/includes/header.php](/code/files/contracts/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/chart.min.js](/code/files/assets/js/chart.min.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)


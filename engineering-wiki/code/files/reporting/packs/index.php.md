# reporting/packs/index.php

> PHP - 128 lines

Reporting -> Report Packs: every pack the analyst can open.

Packs are private until shared (see includes/report_packs/access.php). This page
lists the ones you own and the ones shared with you, and is where you make,
copy, share and delete them. Designing happens in designer.php.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [reporting/includes/header.php](/code/files/reporting/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/report-packs/list.js](/code/files/assets/js/report-packs/list.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)


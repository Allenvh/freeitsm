# domains/table/index.php

> PHP - 54 lines

Domains — full-screen table view (#154).

Thin page over the shared data-table engine (assets/js/data-table.js +
assets/css/data-table.css): column picker, sort, search, per-column filters,
CSV export, saved views and inline editing, with no code of its own beyond
the column list in assets/js/domains-table.js.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [domains/includes/header.php](/code/files/domains/includes/header.php.md), [includes/data-table-skeleton.php](/code/files/includes/data-table-skeleton.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/domains.js](/code/files/assets/js/domains.js.md), [assets/js/data-table.js](/code/files/assets/js/data-table.js.md), [assets/js/domains-table.js](/code/files/assets/js/domains-table.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)


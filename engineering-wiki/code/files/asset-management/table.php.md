# asset-management/table.php

> PHP - 138 lines

Asset Management — Full-screen table view

Thin page over the shared data-table engine (assets/js/data-table.js +
assets/css/data-table.css). Read-only: clicking a row deep-links to the
split-pane view for that asset. Adds PDF export on top of the shared CSV.
The asset-specific columns + loading live in assets/js/asset-table.js.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md), [asset-management/includes/header.php](/code/files/asset-management/includes/header.php.md), [includes/data-table-skeleton.php](/code/files/includes/data-table-skeleton.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/vendor/jspdf.umd.min.js](/code/files/assets/js/vendor/jspdf.umd.min.js.md), [assets/js/vendor/jspdf.plugin.autotable.min.js](/code/files/assets/js/vendor/jspdf.plugin.autotable.min.js.md), [assets/js/data-table.js](/code/files/assets/js/data-table.js.md), [assets/js/asset-table.js](/code/files/assets/js/asset-table.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)


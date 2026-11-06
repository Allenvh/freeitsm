# change-management/table.php

> PHP - 59 lines

Change Management — Full-screen table view

Thin page over the shared data-table engine (assets/js/data-table.js +
assets/css/data-table.css). Inline-editable for the low-risk list fields
(priority, impact, type, assignee) via api/change-management/update_field.php;
status + the detailed fields stay in the full form (click a row to open it).
The change-specific columns + saves live in assets/js/change-table.js.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [change-management/includes/header.php](/code/files/change-management/includes/header.php.md), [includes/data-table-skeleton.php](/code/files/includes/data-table-skeleton.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/data-table.js](/code/files/assets/js/data-table.js.md), [assets/js/change-table.js](/code/files/assets/js/change-table.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)


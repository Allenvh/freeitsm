# calendar/table/index.php

> PHP - 62 lines

Calendar Module — Full-screen table view

Thin page over the shared data-table engine (assets/js/data-table.js +
assets/css/data-table.css). The calendar-specific bits — columns, inline-edit
save, event loading — live in assets/js/calendar-table.js.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [calendar/includes/header.php](/code/files/calendar/includes/header.php.md), [includes/data-table-skeleton.php](/code/files/includes/data-table-skeleton.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/data-table.js](/code/files/assets/js/data-table.js.md), [assets/js/calendar-table.js](/code/files/assets/js/calendar-table.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)


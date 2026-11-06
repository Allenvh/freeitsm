# tasks/table/index.php

> PHP - 117 lines

Tasks Module — Full-screen table view

Thin page over the shared data-table engine (assets/js/data-table.js +
assets/css/data-table.css). Tasks keeps its My/Team/Analyst sidebar and the
shared right-click context menu; the columns + inline-edit saves live in
assets/js/tasks-table.js.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [tasks/includes/header.php](/code/files/tasks/includes/header.php.md), [includes/data-table-skeleton.php](/code/files/includes/data-table-skeleton.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/data-table.js](/code/files/assets/js/data-table.js.md), [assets/js/tasks-ctx-menu.js](/code/files/assets/js/tasks-ctx-menu.js.md), [assets/js/tasks-table.js](/code/files/assets/js/tasks-table.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)


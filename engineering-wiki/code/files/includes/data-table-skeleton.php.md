# includes/data-table-skeleton.php

> PHP - 81 lines

Shared data-table skeleton (toolbar + table) for the full-screen table views.

Renders the standard markup that assets/js/data-table.js drives (fixed dt-*
element IDs). Pair with assets/css/data-table.css. A standalone page wraps
this in <div class="dt-page">; the tasks module nests it inside its own
flex layout instead.

Options (set before include):
  `$dtShowPdf`           bool   — show the PDF export button (default false)
  `$dtSearchPlaceholder` string — search box placeholder

## How it hangs together

**Included by:** [asset-management/table.php](/code/files/asset-management/table.php.md), [calendar/table/index.php](/code/files/calendar/table/index.php.md), [change-management/table.php](/code/files/change-management/table.php.md), [domains/table/index.php](/code/files/domains/table/index.php.md), [tasks/table/index.php](/code/files/tasks/table/index.php.md)


# api/projects/export.php

> API endpoint - 101 lines

GET - download a spreadsheet (3.3.0): ?what=portfolio&ids=3,1,7&format=csv|xlsx the projects in the portfolio's view, in its order ?what=raid&project_id=N&format=csv|xlsx one project's whole RAID log, open and closed

The portfolio filters and sorts in the browser, so the page sends the ids it
is showing; only ids this analyst may see (projectListRows) are written, so
the list cannot be widened by editing the URL. Writing is includes/spreadsheet.php:
every .xlsx cell is text, and CSV cells are guarded against formula injection.

## The endpoint

| | |
|---|---|
| URL | `api/projects/export.php` |
| Protected by | Module access: projects (requireModuleAccessJson) (via includes/projects/api_bootstrap.php); Signed-in analyst (session check) (via includes/i18n.php) |
| Reads | `GET format`, `GET what`, `GET ids`, `GET project_id` |
| Called from | [assets/js/projects-portfolio.js](/code/files/assets/js/projects-portfolio.js.md), [assets/js/projects-tools.js](/code/files/assets/js/projects-tools.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 35 |  | read [projects](/code/tables/projects.md) |  |

## How it hangs together

**Includes:** [includes/projects/api_bootstrap.php](/code/files/includes/projects/api_bootstrap.php.md), [includes/spreadsheet.php](/code/files/includes/spreadsheet.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/projects/settings.php](/code/files/includes/projects/settings.php.md), [includes/projects/budget.php](/code/files/includes/projects/budget.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md)

**Calls:** [isMultiTenant()](/code/functions/isMultiTenant.md), [projectApiFail()](/code/functions/projectApiFail.md), [projectApiRun()](/code/functions/projectApiRun.md), [projectBudgetReady()](/code/functions/projectBudgetReady.md), [projectBudgetTotals()](/code/functions/projectBudgetTotals.md), [projectCode()](/code/functions/projectCode.md), [projectListRows()](/code/functions/projectListRows.md), [projectPriorities()](/code/functions/projectPriorities.md), [projectScaleLabels()](/code/functions/projectScaleLabels.md), [spreadsheetWriteCsv()](/code/functions/spreadsheetWriteCsv.md), [spreadsheetWriteXlsx()](/code/functions/spreadsheetWriteXlsx.md), [t()](/code/functions/t.md)


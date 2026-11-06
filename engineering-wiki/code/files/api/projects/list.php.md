# api/projects/list.php

> API endpoint - 22 lines

GET - the portfolio: every project this analyst may see, with progress and health worked out from its tasks. Filters: q, status, mine=1.

Also the no-cron fallback for project alerts: at most every 15 minutes,
opening the portfolio runs the scan (includes/projects/alerts.php).

## The endpoint

| | |
|---|---|
| URL | `api/projects/list.php` |
| Protected by | Module access: projects (requireModuleAccessJson) (via includes/projects/api_bootstrap.php) |
| Reads | `GET q`, `GET status`, `GET mine` |
| Called from | [assets/js/tasks.js](/code/files/assets/js/tasks.js.md) |

## How it hangs together

**Includes:** [includes/projects/api_bootstrap.php](/code/files/includes/projects/api_bootstrap.php.md), [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md), [includes/projects/settings.php](/code/files/includes/projects/settings.php.md)

**Calls:** [isMultiTenant()](/code/functions/isMultiTenant.md), [projectAlertsOpportunistic()](/code/functions/projectAlertsOpportunistic.md), [projectApiOk()](/code/functions/projectApiOk.md), [projectApiRun()](/code/functions/projectApiRun.md), [projectCanCreate()](/code/functions/projectCanCreate.md), [projectListRows()](/code/functions/projectListRows.md)


# api/system/cost_centres.php

> API endpoint - 198 lines

API: System -> Cost Centres (GH #160, stage 1).

GET  ?action=list&company_id=N                    the company's cost centres (+ the companies to choose from);
                                                    every reachable company's when the header switcher is on "All companies"
  POST {action:'save', id?, company_id, code, name, description, parent_id, is_active}
  POST {action:'delete', id}
  GET  ?action=export&company_id=N&format=xlsx|csv  download the list
  POST multipart: action=import, company_id, file, deactivate_missing=0|1, apply=0|1
       apply=0 is the preview: the same run, stopped before it writes.

Administrators only. Every rule lives in CostCentresService, shared with the
REST API's /cost-centres endpoints, so the screen, an import and an API sync
can never disagree about what a valid cost centre is.

## The endpoint

| | |
|---|---|
| URL | `api/system/cost_centres.php` |
| Protected by | Administrators only (admin_api_guard.php) |
| Reads | `POST action`, `GET action`, `POST company_id`, `GET company_id`, `GET format`, `POST apply`, `POST deactivate_missing` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [costCentreColumns()](/code/functions/costCentreColumns.md) | 25 | The columns of an import/export, and the headings each is recognised by. |
| [costCentreJsonFail()](/code/functions/costCentreJsonFail.md) | 45 | Sends an HTTP status (200 by default) and {success: false, error} as JSON, then stops. |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/cost_centres.php](/code/files/includes/services/cost_centres.php.md), [includes/spreadsheet.php](/code/files/includes/spreadsheet.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [isActiveTenantAll()](/code/functions/isActiveTenantAll.md), [spreadsheetRead()](/code/functions/spreadsheetRead.md), [spreadsheetWriteCsv()](/code/functions/spreadsheetWriteCsv.md), [spreadsheetWriteXlsx()](/code/functions/spreadsheetWriteXlsx.md)


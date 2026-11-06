# api/reporting/packs/context.php

> API endpoint - 39 lines

Report Packs: the values of the header and footer fields for these criteria - {date_from}, {date_to}, {today}, {company} - formatted the viewer's way. POST {criteria}

## The endpoint

| | |
|---|---|
| URL | `api/reporting/packs/context.php` |
| Protected by | Module access: reporting (requireModuleAccessJson) |

## How it hangs together

**Includes:** [includes/report_packs/api_common.php](/code/files/includes/report_packs/api_common.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rpFail()](/code/functions/rpFail.md), [rpFmtDate()](/code/functions/rpFmtDate.md), [rpInitLocale()](/code/functions/rpInitLocale.md), [rpInput()](/code/functions/rpInput.md), [rpOut()](/code/functions/rpOut.md), [rpResolveRange()](/code/functions/rpResolveRange.md), [rpTenantClause()](/code/functions/rpTenantClause.md), [rpTenantLabel()](/code/functions/rpTenantLabel.md), [t()](/code/functions/t.md)


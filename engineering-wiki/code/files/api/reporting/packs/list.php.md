# api/reporting/packs/list.php

> API endpoint - 24 lines

Report Packs: every pack the signed-in analyst can open, with their role on it. GET

## The endpoint

| | |
|---|---|
| URL | `api/reporting/packs/list.php` |
| Protected by | Module access: reporting (requireModuleAccessJson) |

## How it hangs together

**Includes:** [includes/report_packs/api_common.php](/code/files/includes/report_packs/api_common.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rpFail()](/code/functions/rpFail.md), [rpFmtDateTime()](/code/functions/rpFmtDateTime.md), [rpInitLocale()](/code/functions/rpInitLocale.md), [rpListPacks()](/code/functions/rpListPacks.md), [rpOut()](/code/functions/rpOut.md), [t()](/code/functions/t.md)


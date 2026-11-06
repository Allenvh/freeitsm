# api/reporting/packs/get.php

> API endpoint - 42 lines

Report Packs: one pack's design, for the designer or for export. GET ?id=N

## The endpoint

| | |
|---|---|
| URL | `api/reporting/packs/get.php` |
| Protected by | Module access: reporting (requireModuleAccessJson) |
| Reads | `GET id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 18 |  | read [report_packs](/code/tables/report_packs.md), read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [includes/report_packs/api_common.php](/code/files/includes/report_packs/api_common.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rpCleanDesign()](/code/functions/rpCleanDesign.md), [rpDefaultDesign()](/code/functions/rpDefaultDesign.md), [rpFail()](/code/functions/rpFail.md), [rpInitLocale()](/code/functions/rpInitLocale.md), [rpOut()](/code/functions/rpOut.md), [rpRole()](/code/functions/rpRole.md), [t()](/code/functions/t.md)


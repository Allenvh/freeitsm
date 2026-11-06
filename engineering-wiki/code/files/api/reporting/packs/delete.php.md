# api/reporting/packs/delete.php

> API endpoint - 25 lines

Report Packs: delete a pack and its shares. Owner only. POST {id}

## The endpoint

| | |
|---|---|
| URL | `api/reporting/packs/delete.php` |
| Protected by | Module access: reporting (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 19 |  | **delete** [report_packs](/code/tables/report_packs.md) |  |

## How it hangs together

**Includes:** [includes/report_packs/api_common.php](/code/files/includes/report_packs/api_common.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rpFail()](/code/functions/rpFail.md), [rpInitLocale()](/code/functions/rpInitLocale.md), [rpInput()](/code/functions/rpInput.md), [rpOut()](/code/functions/rpOut.md), [rpRole()](/code/functions/rpRole.md), [t()](/code/functions/t.md)


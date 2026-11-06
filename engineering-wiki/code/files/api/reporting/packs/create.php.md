# api/reporting/packs/create.php

> API endpoint - 44 lines

Report Packs: make a new pack (with the starter design), or a copy of one. POST {name, description?, copy_of?}

A copy belongs to whoever made it and is shared with nobody: copying a pack you
can only VIEW is how you get one of your own to change.

## The endpoint

| | |
|---|---|
| URL | `api/reporting/packs/create.php` |
| Protected by | Module access: reporting (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 27 |  | read [report_packs](/code/tables/report_packs.md) |  |
| 34 |  | **insert** [report_packs](/code/tables/report_packs.md) (`name`, `description`, `owner_id`, `design`, `updated_datetime`, `updated_by`) |  |

## How it hangs together

**Includes:** [includes/report_packs/api_common.php](/code/files/includes/report_packs/api_common.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rpCleanDesign()](/code/functions/rpCleanDesign.md), [rpDefaultDesign()](/code/functions/rpDefaultDesign.md), [rpFail()](/code/functions/rpFail.md), [rpInitLocale()](/code/functions/rpInitLocale.md), [rpInput()](/code/functions/rpInput.md), [rpOut()](/code/functions/rpOut.md), [rpRole()](/code/functions/rpRole.md), [rpStr()](/code/functions/rpStr.md), [t()](/code/functions/t.md)


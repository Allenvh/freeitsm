# api/reporting/packs/save.php

> API endpoint - 50 lines

Report Packs: save a pack's name, description and design. Needs Edit or owner. POST {id, name, description?, design, updated?, force?}

`updated` is the stamp the designer loaded. If somebody else has saved since,
the save is refused with `conflict` rather than silently overwriting their work -
a shared pack with two editors open is exactly when that would happen. `force`
is the "Save mine anyway" answer to that.

## The endpoint

| | |
|---|---|
| URL | `api/reporting/packs/save.php` |
| Protected by | Module access: reporting (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 |  | read [report_packs](/code/tables/report_packs.md), read [analysts](/code/tables/analysts.md) |  |
| 40 |  | **update** [report_packs](/code/tables/report_packs.md) (`name`, `description`, `design`, `updated_datetime`, `updated_by`) |  |

## How it hangs together

**Includes:** [includes/report_packs/api_common.php](/code/files/includes/report_packs/api_common.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rpCleanDesign()](/code/functions/rpCleanDesign.md), [rpFail()](/code/functions/rpFail.md), [rpInitLocale()](/code/functions/rpInitLocale.md), [rpInput()](/code/functions/rpInput.md), [rpOut()](/code/functions/rpOut.md), [rpRole()](/code/functions/rpRole.md), [rpRoleAtLeast()](/code/functions/rpRoleAtLeast.md), [rpStr()](/code/functions/rpStr.md), [t()](/code/functions/t.md)


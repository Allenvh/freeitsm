# api/reporting/packs/shares.php

> API endpoint - 47 lines

Report Packs: who a pack is shared with. Owner only. GET ?id=N the shares, plus the analysts, teams and departments to pick from POST {id, shares} replace the shares with this list

## The endpoint

| | |
|---|---|
| URL | `api/reporting/packs/shares.php` |
| Protected by | Module access: reporting (requireModuleAccessJson) |
| Reads | `GET id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 25 |  | read [report_packs](/code/tables/report_packs.md) |  |
| 32 |  | read [analysts](/code/tables/analysts.md) |  |
| 34 |  | read [teams](/code/tables/teams.md) |  |

## How it hangs together

**Includes:** [includes/report_packs/api_common.php](/code/files/includes/report_packs/api_common.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rpFail()](/code/functions/rpFail.md), [rpInitLocale()](/code/functions/rpInitLocale.md), [rpInput()](/code/functions/rpInput.md), [rpKnownDepartments()](/code/functions/rpKnownDepartments.md), [rpListShares()](/code/functions/rpListShares.md), [rpOut()](/code/functions/rpOut.md), [rpRole()](/code/functions/rpRole.md), [rpSaveShares()](/code/functions/rpSaveShares.md), [t()](/code/functions/t.md)


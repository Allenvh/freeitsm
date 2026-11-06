# api/reporting/packs/block_data.php

> API endpoint - 30 lines

Report Packs: one block's data, for the viewer, under the pack's criteria. POST {handler, opts, criteria}

Deliberately NOT tied to a pack id. The designer previews blocks before they
are saved, and the answer depends only on who is asking: their module access
decides whether the block may show at all, and their companies decide what it
counts (rpBlockData()). So this can show nothing a dashboard would not.

## The endpoint

| | |
|---|---|
| URL | `api/reporting/packs/block_data.php` |
| Protected by | Module access: reporting (requireModuleAccessJson) |

## How it hangs together

**Includes:** [includes/report_packs/api_common.php](/code/files/includes/report_packs/api_common.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rpBlockData()](/code/functions/rpBlockData.md), [rpFail()](/code/functions/rpFail.md), [rpInitLocale()](/code/functions/rpInitLocale.md), [rpInput()](/code/functions/rpInput.md), [rpOut()](/code/functions/rpOut.md), [t()](/code/functions/t.md)


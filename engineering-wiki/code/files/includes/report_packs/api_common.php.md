# includes/report_packs/api_common.php

> PHP - 55 lines

Report Packs: what every api/reporting/packs/ endpoint shares. Each endpoint still calls requireModuleAccessJson('reporting') itself, so the guard is visible in the file (and tests/module-access-coverage.php can see it).

## Functions

| Function | Line | What it does |
|---|--:|---|
| [rpOut()](/code/functions/rpOut.md) | 23 | Sends a JSON reply with the given HTTP status and ends the request. |
| [rpFail()](/code/functions/rpFail.md) | 36 | Sends a failure JSON reply ({success: false, error}) with an HTTP status, 400 by default. |
| [rpInput()](/code/functions/rpInput.md) | 42 | The JSON body of a POST. |
| [rpInitLocale()](/code/functions/rpInitLocale.md) | 49 | Bootstrap i18n and the viewer's timezone/date format, for labels and dates. |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/report_packs/access.php](/code/files/includes/report_packs/access.php.md), [includes/report_packs/design.php](/code/files/includes/report_packs/design.php.md)

**Included by:** [api/reporting/packs/block_data.php](/code/files/api/reporting/packs/block_data.php.md), [api/reporting/packs/catalogue.php](/code/files/api/reporting/packs/catalogue.php.md), [api/reporting/packs/context.php](/code/files/api/reporting/packs/context.php.md), [api/reporting/packs/create.php](/code/files/api/reporting/packs/create.php.md), [api/reporting/packs/delete.php](/code/files/api/reporting/packs/delete.php.md), [api/reporting/packs/get.php](/code/files/api/reporting/packs/get.php.md), [api/reporting/packs/list.php](/code/files/api/reporting/packs/list.php.md), [api/reporting/packs/save.php](/code/files/api/reporting/packs/save.php.md), [api/reporting/packs/shares.php](/code/files/api/reporting/packs/shares.php.md)


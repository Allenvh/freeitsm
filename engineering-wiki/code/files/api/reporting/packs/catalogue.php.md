# api/reporting/packs/catalogue.php

> API endpoint - 38 lines

Report Packs: what the designer can offer this analyst - the toolbox, every handler's option schema, the companies they can report on and the date presets. GET

## The endpoint

| | |
|---|---|
| URL | `api/reporting/packs/catalogue.php` |
| Protected by | Module access: reporting (requireModuleAccessJson) |

## How it hangs together

**Includes:** [includes/report_packs/api_common.php](/code/files/includes/report_packs/api_common.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getAccessibleTenantIds()](/code/functions/getAccessibleTenantIds.md), [getAllTenants()](/code/functions/getAllTenants.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rpCatalogue()](/code/functions/rpCatalogue.md), [rpFail()](/code/functions/rpFail.md), [rpInitLocale()](/code/functions/rpInitLocale.md), [rpOut()](/code/functions/rpOut.md), [t()](/code/functions/t.md)


# api/cost-centres/options.php

> API endpoint - 37 lines

API: the cost centres a record can be charged to (GH #160, stage 2). GET ?company_id=N[&keep=ID]

The active cost centres of company N, plus `keep` when it is the record's
current one and has since been made inactive - see includes/cost_centre_assign.php.
Shared by the asset, contract and person screens, so it asks for any one of
those modules rather than System (where cost centres are managed). A company
the analyst cannot reach is refused; with one company, it is always the Default.

## The endpoint

| | |
|---|---|
| URL | `api/cost-centres/options.php` |
| Protected by | Module access: ? (requireAnyModuleAccessJson) |
| Reads | `GET company_id`, `GET keep` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/cost_centre_assign.php](/code/files/includes/cost_centre_assign.php.md)

**Calls:** [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [costCentreOptions()](/code/functions/costCentreOptions.md), [costCentreRecordTenant()](/code/functions/costCentreRecordTenant.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireAnyModuleAccessJson()](/code/functions/requireAnyModuleAccessJson.md)


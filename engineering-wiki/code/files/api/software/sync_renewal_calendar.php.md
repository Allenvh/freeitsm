# api/software/sync_renewal_calendar.php

> API endpoint - 31 lines

API Endpoint: rebuild the software-renewal calendar events on demand (#1551).

Called when the surface setting is saved, so the calendar matches the choice
immediately rather than at the next licence edit. The work lives in
includes/software_licence_calendar.php — the twin of
api/assets/sync_warranty_calendar.php, which does the same job for warranties.

## The endpoint

| | |
|---|---|
| URL | `api/software/sync_renewal_calendar.php` |
| Protected by | Module access: software (requireModuleAccessJson); Permission: Cap::SOFTWARE_RENEWALS (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/software_licence_calendar.php](/code/files/includes/software_licence_calendar.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [syncSoftwareLicenceCalendar()](/code/functions/syncSoftwareLicenceCalendar.md)


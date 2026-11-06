# api/forms/save_collection.php

> API endpoint - 29 lines

API: Create or rename a form collection. Thin UI adapter over FormsService.

## The endpoint

| | |
|---|---|
| URL | `api/forms/save_collection.php` |
| Protected by | Module access: forms (requireModuleAccessJson); Permission: Cap::FORMS_COLLECTIONS (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


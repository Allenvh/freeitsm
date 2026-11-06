# api/forms/set_collection_closed.php

> API endpoint - 38 lines

API: Close or reopen a form collection.

🔴 Writes to form_collections and nothing else. What closing DOES is the
operator's setting, evaluated when the portal or the fill page asks — never
stamped onto the forms, or reopening would switch three forms back on
including one deliberately kept off the portal.

## The endpoint

| | |
|---|---|
| URL | `api/forms/set_collection_closed.php` |
| Protected by | Module access: forms (requireModuleAccessJson); Permission: Cap::FORMS_COLLECTIONS (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


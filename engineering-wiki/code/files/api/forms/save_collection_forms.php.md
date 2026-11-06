# api/forms/save_collection_forms.php

> API endpoint - 37 lines

API: set which forms belong to a collection, from the collection's side.

Writes `forms.collection_id` only. Submissions already made keep the
collection they were filed under — unlinking a form here must never rewrite
history, which is the whole reason the stamp is a separate column.

## The endpoint

| | |
|---|---|
| URL | `api/forms/save_collection_forms.php` |
| Protected by | Module access: forms (requireModuleAccessJson); Permission: Cap::FORMS_COLLECTIONS (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


# api/forms/get_collection_form_picker.php

> API endpoint - 30 lines

API: every leaf form with the collection it is currently in.

The picker shows where a form already lives, because a form belongs to at
most one collection — so ticking one here TAKES it from somewhere else, and
nobody should discover that afterwards.

## The endpoint

| | |
|---|---|
| URL | `api/forms/get_collection_form_picker.php` |
| Protected by | Module access: forms (requireModuleAccessJson); Permission: Cap::FORMS_COLLECTIONS (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


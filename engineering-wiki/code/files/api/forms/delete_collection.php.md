# api/forms/delete_collection.php

> API endpoint - 33 lines

API: Delete a form collection.

Refused while anything is stamped into it — such a collection can be CLOSED
but not deleted, or the audit trail the stamp exists for goes with it. The
database enforces the same thing, but a constraint error is not an
explanation, so the service says it in a sentence first.

## The endpoint

| | |
|---|---|
| URL | `api/forms/delete_collection.php` |
| Protected by | Module access: forms (requireModuleAccessJson); Permission: Cap::FORMS_COLLECTIONS (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


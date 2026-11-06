# api/change-management/save.php

> API endpoint - 65 lines

API Endpoint: Create or update a change record. Thin UI adapter over ChangesService (create/update). The per-field audit trail, risk scoring, and the change.approved workflow event live there, shared with the REST API's POST/PATCH /changes. This is a full-form save, so it sends every field; the service treats absent keys as untouched.

## The endpoint

| | |
|---|---|
| URL | `api/change-management/save.php` |
| Protected by | Module access: changes (requireModuleAccessJson) |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/changes.php](/code/files/includes/services/changes.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


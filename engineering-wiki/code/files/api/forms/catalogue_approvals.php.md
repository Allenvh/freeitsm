# api/forms/catalogue_approvals.php

> API endpoint - 40 lines

API: the catalogue-request approval inbox (#928).

GET ?filter=mine|all|decided
  -> { success, items: [...], counts: { mine, all, decided } }

Module access only, like the Change approvals inbox: signing off a catalogue request
is everyday service-desk work. The engine (includes/catalogue_approvals.php) still
enforces that only the ASSIGNED approver (or an admin) can actually decide one.

## The endpoint

| | |
|---|---|
| URL | `api/forms/catalogue_approvals.php` |
| Protected by | Module access: forms (requireModuleAccessJson) |
| Reads | `GET filter` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md)

**Calls:** [catalogueApprovalsList()](/code/functions/catalogueApprovalsList.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


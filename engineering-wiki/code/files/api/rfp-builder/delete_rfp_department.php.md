# api/rfp-builder/delete_rfp_department.php

> API endpoint - 39 lines

API Endpoint: Delete RFP department. Documents that reference this department have department_id auto-NULLed via the FK constraint (ON DELETE SET NULL), so no manual cascade is needed.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/delete_rfp_department.php` |
| Protected by | Module access: contracts (requireModuleAccessJson); Permission: Cap::CONTRACTS_RFP_DEPARTMENTS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 32 |  | **delete** [rfp_departments](/code/tables/rfp_departments.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


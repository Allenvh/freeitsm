# api/rfp-builder/save_rfp_department.php

> API endpoint - 65 lines

API Endpoint: Save RFP department (create or update)

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/save_rfp_department.php` |
| Protected by | Module access: contracts (requireModuleAccessJson); Permission: Cap::CONTRACTS_RFP_DEPARTMENTS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 41 |  | **update** [rfp_departments](/code/tables/rfp_departments.md) (`name`, `colour`, `sort_order`, `is_active`) |  |
| 47 |  | **insert** [rfp_departments](/code/tables/rfp_departments.md) (`name`, `colour`, `sort_order`, `is_active`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


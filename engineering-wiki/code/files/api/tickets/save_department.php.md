# api/tickets/save_department.php

> API endpoint - 80 lines

API Endpoint: Save department (create or update)

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_department.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_DEPARTMENTS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 37 |  | **update** [departments](/code/tables/departments.md) (`name`, `description`, `display_order`, `is_active`) |  |
| 42 |  | **insert** [departments](/code/tables/departments.md) (`name`, `description`, `display_order`, `is_active`) |  |
| 56 |  | read [departments](/code/tables/departments.md) |  |
| 59 |  | **update** [departments](/code/tables/departments.md) (`default_sensitivity`) |  |
| 65 |  | read [tickets](/code/tables/tickets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ticketSensitivityApplyDefaults()](/code/functions/ticketSensitivityApplyDefaults.md), [ticketSensitivityNormalise()](/code/functions/ticketSensitivityNormalise.md), [ticketSensitivityReady()](/code/functions/ticketSensitivityReady.md)


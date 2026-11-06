# api/tickets/delete_department.php

> API endpoint - 40 lines

API Endpoint: Delete department

## The endpoint

| | |
|---|---|
| URL | `api/tickets/delete_department.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_DEPARTMENTS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 29 |  | **delete** [departments](/code/tables/departments.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


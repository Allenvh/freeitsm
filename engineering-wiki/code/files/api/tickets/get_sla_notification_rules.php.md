# api/tickets/get_sla_notification_rules.php

> API endpoint - 54 lines

API: List all SLA breach notification rules + auxiliary data needed to render the settings UI in a single round-trip.

Returns: { rules: [...], departments: [...], analysts: [...] }
  - rules are ordered global-default-first, then by department name
  - departments + analysts include only active rows (id, name)

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_sla_notification_rules.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 24 |  | read [sla_notification_rules](/code/tables/sla_notification_rules.md), read [departments](/code/tables/departments.md), read [analysts](/code/tables/analysts.md) |  |
| 42 |  | read [departments](/code/tables/departments.md) |  |
| 43 |  | read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


# api/tickets/delete_sla_notification_rule.php

> API endpoint - 33 lines

API: Delete an SLA breach notification rule. POST { id: <int> } — soft-delete not used (rules can be toggled inactive instead if the admin wants to keep the config).

## The endpoint

| | |
|---|---|
| URL | `api/tickets/delete_sla_notification_rule.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_SLA (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 26 |  | **delete** [sla_notification_rules](/code/tables/sla_notification_rules.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


# api/tickets/save_sla_global_settings.php

> API endpoint - 83 lines

API: Save the seven SLA global settings (system_settings rows).

POST JSON: { sla_enforce_from, sla_priority_change_behaviour, sla_reopen_behaviour,
             sla_warning_threshold_percent, sla_notify_assignee_at_warning,
             sla_notify_lead_at_breach, sla_first_response_definition }

Validation: enums are checked against the allowed set; the threshold is bounded
1-100; sla_enforce_from must parse as a datetime or be empty/null.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_sla_global_settings.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_SLA (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 70 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


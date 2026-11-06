# api/tickets/save_sla_notification_rule.php

> API endpoint - 130 lines

API: Create or update an SLA breach notification rule.

POST JSON body:
  id (optional — update if present, else create)
  department_id (int|null — null = global default rule)
  trigger_type ('warning' | 'breach')
  target_type ('response' | 'resolution' | 'both')
  notify_assignee, notify_department_teams (bool)
  notify_analyst_id (int|null)
  notify_emails (string — comma/semicolon/newline separated; normalised here)
  is_active (bool, default true)

Enforces:
  - At least one recipient (otherwise the rule would silently no-op)
  - One rule per (department_id, trigger_type, target_type) — refuse overlap
  - target_type 'both' refuses to coexist with explicit 'response'/'resolution'
    for the same dept+trigger (the 'both' rule would shadow them)
  - Email format validation

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_sla_notification_rule.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_SLA (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 80 |  | read [sla_notification_rules](/code/tables/sla_notification_rules.md) |  |
| 101 |  | **update** [sla_notification_rules](/code/tables/sla_notification_rules.md) (`department_id`, `trigger_type`, `target_type`, `notify_assignee`, `notify_department_teams`, `notify_analyst_id`, `notify_emails`, `is_active`) |  |
| 113 |  | **insert** [sla_notification_rules](/code/tables/sla_notification_rules.md) (`department_id`, `trigger_type`, `target_type`, `notify_assignee`, `notify_department_teams`, `notify_analyst_id`, `notify_emails`, `is_active`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


# api/tickets/save_email_template.php

> API endpoint - 121 lines

API Endpoint: Create or update an email template POST: { id, name, event_trigger, subject_template, body_template, is_active, display_order }

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_email_template.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_EMAIL_TEMPLATES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 79 |  | **update** [ticket_email_templates](/code/tables/ticket_email_templates.md) (`name`, `event_trigger`, `subject_template`, `body_template`, `is_active`, `display_order`, `updated_datetime`) |  |
| 86 |  | **insert** [ticket_email_templates](/code/tables/ticket_email_templates.md) (`name`, `event_trigger`, `subject_template`, `body_template`, `is_active`, `display_order`) |  |
| 106 |  | **delete** [ticket_email_template_rules](/code/tables/ticket_email_template_rules.md) |  |
| 108 |  | **insert** [ticket_email_template_rules](/code/tables/ticket_email_template_rules.md) (`template_id`, `match_type`, `match_value`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


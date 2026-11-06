# api/tickets/delete_email_template.php

> API endpoint - 45 lines

API Endpoint: Delete an email template POST: { id }

## The endpoint

| | |
|---|---|
| URL | `api/tickets/delete_email_template.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_EMAIL_TEMPLATES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 34 |  | **delete** [ticket_email_template_rules](/code/tables/ticket_email_template_rules.md) |  |
| 36 |  | **delete** [ticket_email_templates](/code/tables/ticket_email_templates.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


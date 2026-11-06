# api/tickets/get_email_templates.php

> API endpoint - 49 lines

API Endpoint: Get all email templates GET: Returns all templates ordered by display_order, id

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_email_templates.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 22 |  | read [ticket_email_templates](/code/tables/ticket_email_templates.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/template_email.php](/code/files/includes/template_email.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [templateEventAudience()](/code/functions/templateEventAudience.md), [templateRulesByTemplate()](/code/functions/templateRulesByTemplate.md)


# api/tickets/delete_reply_template.php

> API endpoint - 53 lines

API Endpoint: delete a reply template.

POST { id }

Same split as the save: your own is yours to bin, a shared one is a settings action.
The scope is read from the DATABASE, never from the request — a client that could
name its own scope could delete anything.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/delete_reply_template.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_REPLY_TEMPLATES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 45 |  | **delete** [ticket_reply_templates](/code/tables/ticket_reply_templates.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/reply_templates.php](/code/files/includes/reply_templates.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [replyTemplateWriteScope()](/code/functions/replyTemplateWriteScope.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


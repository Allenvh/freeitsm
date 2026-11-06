# api/messaging/save_template.php

> API endpoint - 54 lines

API Endpoint: create/update a messaging template definition.

## The endpoint

| | |
|---|---|
| URL | `api/messaging/save_template.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_MESSAGING (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 42 |  | **update** [messaging_templates](/code/tables/messaging_templates.md) (`name`, `provider`, `language`, `provider_ref`, `body`, `is_active`) |  |
| 46 |  | **insert** [messaging_templates](/code/tables/messaging_templates.md) (`name`, `provider`, `language`, `provider_ref`, `body`, `is_active`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


# api/messaging/get_templates.php

> API endpoint - 63 lines

API Endpoint: list messaging templates. ?manage=1 → all templates (for the settings page) ?provider=twilio → only that provider's active templates (for the composer picker)

## The endpoint

| | |
|---|---|
| URL | `api/messaging/get_templates.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET manage`, `GET provider` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 27 |  | read [messaging_templates](/code/tables/messaging_templates.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [messagingTemplateVarCount()](/code/functions/messagingTemplateVarCount.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


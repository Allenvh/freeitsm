# api/messaging/save_base_url.php

> API endpoint - 57 lines

API Endpoint: save the public base URL used to build messaging webhook URLs.

Self-hosters set this once (their public domain, or the ngrok address while
testing) so the Messaging settings page shows a copy-paste-ready webhook URL
instead of "localhost". Stored as system_settings 'messaging_public_base_url'.
Only the origin (scheme://host[:port]) is kept — any path is stripped.

## The endpoint

| | |
|---|---|
| URL | `api/messaging/save_base_url.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_MESSAGING (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 46 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


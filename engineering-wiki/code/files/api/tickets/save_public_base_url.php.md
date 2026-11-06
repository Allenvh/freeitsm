# api/tickets/save_public_base_url.php

> API endpoint - 67 lines

API Endpoint: save the public web address used in links FreeITSM sends out.

Stored as system_settings 'public_base_url'.

⚠️ THE PATH IS KEPT, unlike api/messaging/save_base_url.php which deliberately
strips it. That is not an inconsistency to tidy away — the two values are used
differently. The messaging one has the app root appended to it afterwards by
`messagingWebhookUrl()`; this one IS the root, so an install at
`https://example.com/freeitsm-app` must be able to say so. Strip the path here
and every [ticket_url] on such an install points at a page that is not there.

The query string and fragment ARE stripped: they are always a paste accident
(somebody copies the address bar while looking at a ticket), and keeping them
would append a second `?` to every link built from this.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_public_base_url.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_EMAIL_TEMPLATES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 56 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


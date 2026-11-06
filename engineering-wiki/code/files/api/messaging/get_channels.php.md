# api/messaging/get_channels.php

> API endpoint - 93 lines

API Endpoint: list messaging channels (WhatsApp etc.) for the settings page. Never returns raw credentials — only whether they're set, plus the public webhook URL to paste into the provider console.

## The endpoint

| | |
|---|---|
| URL | `api/messaging/get_channels.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_MESSAGING (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 50 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 58 |  | read [messaging_channels](/code/tables/messaging_channels.md) |  |
| 59 |  | read [messaging_channels](/code/tables/messaging_channels.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md)

**Calls:** [analystHasCapability()](/code/functions/analystHasCapability.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [messagingDecodeCredentials()](/code/functions/messagingDecodeCredentials.md), [messagingWebhookUrl()](/code/functions/messagingWebhookUrl.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [sessionIsAdmin()](/code/functions/sessionIsAdmin.md)


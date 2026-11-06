# api/messaging/telegram_connect.php

> API endpoint - 70 lines

API Endpoint: register a saved Telegram channel's webhook with Telegram.

POST JSON { id }

The Connect button in Tickets → Settings → Messaging. Calls Telegram's
setWebhook with this channel's webhook URL and its stored secret token, using
the stored bot token - all server-side.

Added at merge time for PR #159, which instead showed a ready-made
`curl https://api.telegram.org/bot<TOKEN>/setWebhook?...` command to copy and
run. That put the bot token in the browser, the clipboard and the admin's shell
history, and asked an IT manager to run a terminal command. Here the token
never leaves the server and the result is shown in the dialog.

Same gate as saving a channel: Tickets module + the messaging capability, and
the channel must be one this analyst may administer.

## The endpoint

| | |
|---|---|
| URL | `api/messaging/telegram_connect.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_MESSAGING (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessChannel()](/code/functions/analystCanAccessChannel.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [loadMessagingChannel()](/code/functions/loadMessagingChannel.md), [messagingProvider()](/code/functions/messagingProvider.md), [messagingWebhookUrl()](/code/functions/messagingWebhookUrl.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


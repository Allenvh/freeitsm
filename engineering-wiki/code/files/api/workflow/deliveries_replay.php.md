# api/workflow/deliveries_replay.php

> API endpoint - 38 lines

API: replay one webhook delivery — resets it to 'pending' so the cron worker sends it again. Body: { id }.

## The endpoint

| | |
|---|---|
| URL | `api/workflow/deliveries_replay.php` |
| Protected by | Module access: workflow (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [webhookReplay()](/code/functions/webhookReplay.md), [webhookReplayBlockedReason()](/code/functions/webhookReplayBlockedReason.md)


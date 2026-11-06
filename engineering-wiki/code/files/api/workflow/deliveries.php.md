# api/workflow/deliveries.php

> API endpoint - 85 lines

API: list outbound webhook deliveries for the log page. Read-only. Optional filters: ?status=pending|delivering|delivered|failed|dead, ?q=<url text>.

## The endpoint

| | |
|---|---|
| URL | `api/workflow/deliveries.php` |
| Protected by | Module access: system (requireModuleAccessJson) |
| Reads | `GET status`, `GET q`, `GET limit` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 36 |  | read [webhook_deliveries](/code/tables/webhook_deliveries.md) |  |
| 41 |  | read [webhook_deliveries](/code/tables/webhook_deliveries.md), read [workflows](/code/tables/workflows.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [webhookDecrypt()](/code/functions/webhookDecrypt.md), [webhookDiagnoseError()](/code/functions/webhookDiagnoseError.md), [webhookRedactUrl()](/code/functions/webhookRedactUrl.md)


# api/workflow/webhook_test.php

> API endpoint - 158 lines

API: Send a TEST outbound webhook from the workflow editor — the "Send test" button. Takes the send_webhook action's current config, renders its templates against a representative sample payload, and delivers it synchronously so the user sees the endpoint's real response before saving the workflow.

Unlike a real fire this does NOT enqueue or touch the delivery log — it's a
one-off preview. It uses the exact same request builder (WorkflowEngine::
buildWebhookRequest) and transport (webhookHttpSend) as a real delivery, so
"it worked in test" means it will work live.

Body: { url, preset?, message?, body?, secret?, trigger_event? }

## The endpoint

| | |
|---|---|
| URL | `api/workflow/webhook_test.php` |
| Protected by | Module access: workflow (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 87 |  | read [tickets](/code/tables/tickets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md), [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md), [api/v1/lib/response.php](/code/files/api/v1/lib/response.php.md), [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md)

**Calls:** [apiSerializeTicket()](/code/functions/apiSerializeTicket.md), [apiTicketSelect()](/code/functions/apiTicketSelect.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requestScheme()](/code/functions/requestScheme.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ticketSensitivityReady()](/code/functions/ticketSensitivityReady.md), [webhookDecrypt()](/code/functions/webhookDecrypt.md), [webhookDiagnoseError()](/code/functions/webhookDiagnoseError.md), [webhookHttpSend()](/code/functions/webhookHttpSend.md)


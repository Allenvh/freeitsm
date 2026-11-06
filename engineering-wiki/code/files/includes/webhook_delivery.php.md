# includes/webhook_delivery.php

> PHP - 464 lines

Outbound webhook delivery — shared between the workflow engine (which ENQUEUES) and the cron worker (which DELIVERS).

The `send_webhook` workflow action builds the request (URL, headers incl. any
HMAC signature, rendered JSON body) and calls `webhookEnqueue()` — returning
instantly, so a slow or dead endpoint never blocks the host request. The
worker (cron/webhook_deliveries.php) later picks up due rows and calls
`webhookRunQueue()`, which delivers each with retries and exponential backoff,
dead-lettering after max_attempts. Every outcome is recorded on the row, so
the delivery log is the single source of truth for what was sent and when.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [webhookEncryptionAvailable()](/code/functions/webhookEncryptionAvailable.md) | 36 | True when an encryption key is actually usable — drives the honesty banner. |
| [webhookEncrypt()](/code/functions/webhookEncrypt.md) | 50 | Encrypt if we can; fall back to plaintext rather than breaking the webhook. |
| [webhookDecrypt()](/code/functions/webhookDecrypt.md) | 64 | Decrypt; plaintext passes straight through (pre-encryption rows still work). |
| [webhookRedactUrl()](/code/functions/webhookRedactUrl.md) | 82 | A URL safe to show in the delivery log: keeps the bit that identifies WHERE it went, hides the bit that lets you POST there. A Discord webhook URL ends in a token; printing it in a log everyone can read defeats encrypting it. |
| [webhookPayloadRetentionDays()](/code/functions/webhookPayloadRetentionDays.md) | 131 | Days to keep payload bodies. 0 = never store, -1 = forever. |
| [webhookPurgePayloads()](/code/functions/webhookPurgePayloads.md) | 148 | Blank the stored request/response bodies of deliveries past the retention window. Returns how many rows were scrubbed. Called by the cron worker after each queue run, so retention is enforced without another scheduled task. |
| [webhookBackoffSeconds()](/code/functions/webhookBackoffSeconds.md) | 172 | Seconds to wait before the next retry, keyed by the attempt that just failed. |
| [webhookDiagnoseError()](/code/functions/webhookDiagnoseError.md) | 196 | Turn a raw cURL transport error into something a human can act on. |
| [webhookEnqueue()](/code/functions/webhookEnqueue.md) | 263 | Queue a webhook for asynchronous delivery. `$d` keys: workflow_id, execution_id, preset, url, method, headers (array of header lines), body (string), max_attempts. Returns the new delivery id. |
| [webhookHttpSend()](/code/functions/webhookHttpSend.md) | 299 | Perform the outbound HTTP request for a webhook and return the raw outcome. Shared by the delivery worker and the editor's "Send test" preview so both use identical transport (method, timeouts, TLS verification) — the test can never behave differently from a real delivery. |
| [webhookAttemptDelivery()](/code/functions/webhookAttemptDelivery.md) | 326 | Attempt to deliver one already-claimed row (status must be 'delivering'). Updates the row with the outcome and returns 'delivered' \| 'failed' \| 'dead'. |
| [webhookScrubIfNoRetention()](/code/functions/webhookScrubIfNoRetention.md) | 383 | Retention "don't store": the payload had to exist long enough for the worker to send it, so we scrub it the instant the delivery settles rather than waiting for the next purge sweep. A no-op on every other retention setting. |
| [webhookRunQueue()](/code/functions/webhookRunQueue.md) | 402 | Deliver up to `$limit` due rows. Claims each atomically (so overlapping worker runs never double-send). Returns counts. |
| [webhookReplay()](/code/functions/webhookReplay.md) | 432 | Reset a delivery so the worker sends it again (the "Replay" button). |
| [webhookReplayBlockedReason()](/code/functions/webhookReplayBlockedReason.md) | 450 | Why a replay was refused — so the UI can say "the payload was purged by your retention setting" rather than a generic failure. Replay re-sends the STORED body, so a purged row has nothing to send and must not be re-queued (that would POST an empty payload to a live endpoint). |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 133 | `webhookPayloadRetentionDays` | read [system_settings](/code/tables/system_settings.md) |  |
| 156 | `webhookPurgePayloads` | **update** [webhook_deliveries](/code/tables/webhook_deliveries.md) (`request_body`, `response_snippet`, `payload_purged`, `updated_datetime`) |  |
| 265 | `webhookEnqueue` | **insert** [webhook_deliveries](/code/tables/webhook_deliveries.md) (`workflow_id`, `execution_id`, `preset`, `url`, `method`, `request_headers`, `request_body`, `status`, `attempts`, `max_attempts`, `next_attempt_at`, `created_datetime`) |  |
| 346 | `webhookAttemptDelivery` | **update** [webhook_deliveries](/code/tables/webhook_deliveries.md) (`status`, `attempts`, `last_status_code`, `last_error`, `response_snippet`, `delivered_datetime`, `next_attempt_at`, `updated_datetime`) |  |
| 359 | `webhookAttemptDelivery` | **update** [webhook_deliveries](/code/tables/webhook_deliveries.md) (`status`, `attempts`, `last_status_code`, `last_error`, `response_snippet`, `next_attempt_at`, `updated_datetime`) |  |
| 368 | `webhookAttemptDelivery` | **update** [webhook_deliveries](/code/tables/webhook_deliveries.md) (`status`, `attempts`, `last_status_code`, `last_error`, `response_snippet`, `next_attempt_at`, `updated_datetime`) |  |
| 387 | `webhookScrubIfNoRetention` | **update** [webhook_deliveries](/code/tables/webhook_deliveries.md) (`request_body`, `response_snippet`, `payload_purged`) |  |
| 405 | `webhookRunQueue` | read [webhook_deliveries](/code/tables/webhook_deliveries.md) |  |
| 413 | `webhookRunQueue` | **update** [webhook_deliveries](/code/tables/webhook_deliveries.md) (`status`, `updated_datetime`) |  |
| 414 | `webhookRunQueue` | read [webhook_deliveries](/code/tables/webhook_deliveries.md) |  |
| 434 | `webhookReplay` | **update** [webhook_deliveries](/code/tables/webhook_deliveries.md) (`status`, `next_attempt_at`, `last_error`, `updated_datetime`) |  |
| 451 | `webhookReplayBlockedReason` | read [webhook_deliveries](/code/tables/webhook_deliveries.md) |  |

## How it hangs together

**Includes:** [includes/encryption.php](/code/files/includes/encryption.php.md)

**Included by:** [api/workflow/deliveries.php](/code/files/api/workflow/deliveries.php.md), [api/workflow/deliveries_replay.php](/code/files/api/workflow/deliveries_replay.php.md), [api/workflow/get.php](/code/files/api/workflow/get.php.md), [api/workflow/save.php](/code/files/api/workflow/save.php.md), [api/workflow/save_payload_retention.php](/code/files/api/workflow/save_payload_retention.php.md), [api/workflow/webhook_test.php](/code/files/api/workflow/webhook_test.php.md), [cron/webhook_deliveries.php](/code/files/cron/webhook_deliveries.php.md), [system/webhooks/index.php](/code/files/system/webhooks/index.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md)

**Calls:** [decryptValue()](/code/functions/decryptValue.md), [encryptValue()](/code/functions/encryptValue.md), [getEncryptionKey()](/code/functions/getEncryptionKey.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)


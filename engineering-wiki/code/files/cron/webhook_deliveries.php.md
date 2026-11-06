# cron/webhook_deliveries.php

> PHP - 91 lines

Outbound webhook delivery worker — cron entry point.

Delivers queued rows from `webhook_deliveries` (enqueued by the `send_webhook`
workflow action) asynchronously, with retries + exponential backoff and
dead-lettering after max_attempts — so a slow or dead endpoint never blocks
the request that fired the webhook. Delivery latency is roughly your cron
interval; every 1 minute is a good default. See docs/webhook-cron-setup.md.

SECURITY (HTTP invocation only), mirroring cron/sla_breach_check.php:
  - Shared-secret token via ?token=<value> matching `webhook_cron_token`
    in system_settings (hash_equals; seeded by Database Verification).
  - Min interval between runs (`webhook_cron_min_interval_seconds`, default
    20s), enforced for CLI + HTTP, defeats double-scheduling / runaway loops.
CLI invocation skips the token (there is no untrusted caller).

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 33 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 69 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |
| 82 |  | **delete** [webhook_deliveries](/code/tables/webhook_deliveries.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md), [webhookPurgePayloads()](/code/functions/webhookPurgePayloads.md), [webhookRunQueue()](/code/functions/webhookRunQueue.md)


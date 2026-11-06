# cron/attachment_extract.php

> PHP - 121 lines

Attachment text extraction worker — cron entry point (discussion #53, tier 2).

Reads attachments sitting at `pending` in `attachment_text` and asks the
configured extraction service (System → Integrations → Apache Tika) for their
text. Tier 1 formats never reach here; they are read inline in milliseconds.
This is for the slow ones — PDFs, and anything needing OCR, which can take
minutes per document and must not run inside a web request.

Every 5 minutes is a sensible default. Latency is roughly your cron interval.

⚠️ It is NOT the only way the queue drains. FreeITSM also takes a few items
opportunistically when somebody is already using it, so an installation with
no cron still works — see includes/search/extract_queue.php. Either mechanism
can be switched off in Tickets → Settings → Indexing.

SECURITY (HTTP invocation only), mirroring cron/webhook_deliveries.php:
  - Shared-secret token via ?token=<value> matching `webhook_cron_token` in
    system_settings (hash_equals). The same token the other crons use, so an
    administrator has one secret to manage rather than four.
CLI invocation skips the token — there is no untrusted caller.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 42 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 77 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 92 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/search/extract_queue.php](/code/files/includes/search/extract_queue.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md), [extractQueueDepth()](/code/functions/extractQueueDepth.md), [extractQueueDrain()](/code/functions/extractQueueDrain.md), [extractQueueSettingOn()](/code/functions/extractQueueSettingOn.md), [tikaConfigured()](/code/functions/tikaConfigured.md)


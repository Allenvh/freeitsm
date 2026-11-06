# api/workflow/save_payload_retention.php

> API endpoint - 54 lines

API: set how long sent webhook payload bodies are kept.

Body: { days }  —  0 = don't store at all, -1 = as long as the delivery row
lives, otherwise a positive number of days.

This governs `webhook_deliveries.request_body` / `response_snippet`, which
hold the exact payload that was sent — with the Full-record preset that's an
entire ticket in plain text. Distinct from `webhook_delivery_retention_days`,
which deletes the whole row; this one scrubs just the bodies and keeps the
delivery record for audit.

## The endpoint

| | |
|---|---|
| URL | `api/workflow/save_payload_retention.php` |
| Protected by | Administrators only (admin_api_guard.php); Administrators only (requireAdminJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 42 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireAdminJson()](/code/functions/requireAdminJson.md), [webhookPurgePayloads()](/code/functions/webhookPurgePayloads.md)


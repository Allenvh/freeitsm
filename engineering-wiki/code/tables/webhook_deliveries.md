# webhook_deliveries

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `workflow_id` | `INT NULL` |  |
| `execution_id` | `INT NULL` |  |
| `preset` | `VARCHAR(20) NULL` |  |
| `url` | `VARCHAR(2000) NOT NULL` |  |
| `method` | `VARCHAR(10) NOT NULL DEFAULT 'POST'` |  |
| `request_headers` | `TEXT NULL` |  |
| `request_body` | `MEDIUMTEXT NULL` |  |
| `payload_purged` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `status` | `VARCHAR(20) NOT NULL DEFAULT 'pending'` |  |
| `attempts` | `INT NOT NULL DEFAULT 0` |  |
| `max_attempts` | `INT NOT NULL DEFAULT 6` |  |
| `next_attempt_at` | `DATETIME NULL` |  |
| `last_status_code` | `INT NULL` |  |
| `last_error` | `VARCHAR(500) NULL` |  |
| `response_snippet` | `MEDIUMTEXT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP` |  |
| `delivered_datetime` | `DATETIME NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3187 |  | update | `workflow_id` |  |
| [cron/webhook_deliveries.php](/code/files/cron/webhook_deliveries.php.md):82 |  | delete |  |  |
| [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md):156 | `webhookPurgePayloads` | update | `request_body`, `response_snippet`, `payload_purged`, `updated_datetime` |  |
| [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md):265 | `webhookEnqueue` | insert | `workflow_id`, `execution_id`, `preset`, `url`, `method`, `request_headers`, `request_body`, `status`, `attempts`, `max_attempts` |  |
| [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md):346 | `webhookAttemptDelivery` | update | `status`, `attempts`, `last_status_code`, `last_error`, `response_snippet`, `delivered_datetime`, `next_attempt_at`, `updated_datetime` |  |
| [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md):359 | `webhookAttemptDelivery` | update | `status`, `attempts`, `last_status_code`, `last_error`, `response_snippet`, `next_attempt_at`, `updated_datetime` |  |
| [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md):368 | `webhookAttemptDelivery` | update | `status`, `attempts`, `last_status_code`, `last_error`, `response_snippet`, `next_attempt_at`, `updated_datetime` |  |
| [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md):387 | `webhookScrubIfNoRetention` | update | `request_body`, `response_snippet`, `payload_purged` |  |
| [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md):413 | `webhookRunQueue` | update | `status`, `updated_datetime` |  |
| [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md):434 | `webhookReplay` | update | `status`, `next_attempt_at`, `last_error`, `updated_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L3196, L3235 |  |
| [api/workflow/deliveries.php](/code/files/api/workflow/deliveries.php.md) | L36, L41 |  |
| [api/workflow/execution_detail.php](/code/files/api/workflow/execution_detail.php.md) | L48 |  |
| [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) | getWatchtowerData L952 |  |
| [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md) | webhookRunQueue L405, webhookRunQueue L414, webhookReplayBlockedReason L451 |  |
| [system/webhooks/index.php](/code/files/system/webhooks/index.php.md) | L69, L82, L107, L133, L143 |  |


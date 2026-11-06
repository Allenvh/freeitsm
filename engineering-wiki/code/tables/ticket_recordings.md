# ticket_recordings

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `ticket_id` | `INT NULL` |  |
| `email_id` | `INT NULL` | Which message the recording came with. NULL = the ticket's opening message, which is what every recording was before replies could carry one — so existing rows are already correct and need no backfill. |
| `recorded_by_user_id` | `INT NULL` |  |
| `filename` | `VARCHAR(255) NOT NULL` |  |
| `original_filename` | `VARCHAR(255) NULL` |  |
| `content_type` | `VARCHAR(100) NOT NULL` |  |
| `file_path` | `VARCHAR(500) NOT NULL` |  |
| `file_size` | `INT NOT NULL` |  |
| `duration_seconds` | `INT NULL` |  |
| `has_audio` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `created_at` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/self-service/upload_recording.php](/code/files/api/self-service/upload_recording.php.md):90 |  | insert | `ticket_id`, `recorded_by_user_id`, `filename`, `original_filename`, `content_type`, `file_path`, `file_size`, `duration_seconds`, `has_audio`, `created_at` |  |
| [includes/ticket_recordings.php](/code/files/includes/ticket_recordings.php.md):67 | `claimPendingRecordings` | update | `ticket_id`, `email_id`, `file_path` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/self-service/get_recording.php](/code/files/api/self-service/get_recording.php.md) | L31 |  |
| [api/self-service/get_ticket_detail.php](/code/files/api/self-service/get_ticket_detail.php.md) | L255 |  |
| [api/system/debug-tools/D002_delete_ticket.php](/code/files/api/system/debug-tools/D002_delete_ticket.php.md) | L341 |  |
| [api/system/debug-tools/D012_ticket_attachments.php](/code/files/api/system/debug-tools/D012_ticket_attachments.php.md) | L576 |  |
| [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md) | L306 |  |
| [includes/ticket_recordings.php](/code/files/includes/ticket_recordings.php.md) | claimPendingRecordings L60 |  |


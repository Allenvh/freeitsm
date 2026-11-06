# email_attachments

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `email_id` | `INT NOT NULL` |  |
| `exchange_attachment_id` | `VARCHAR(255) NULL` |  |
| `filename` | `VARCHAR(255) NOT NULL` |  |
| `content_type` | `VARCHAR(100) NOT NULL` |  |
| `content_id` | `VARCHAR(255) NULL` |  |
| `file_path` | `VARCHAR(500) NOT NULL` |  |
| `file_size` | `INT NOT NULL` |  |
| `is_inline` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/messaging/send_attachment.php](/code/files/api/messaging/send_attachment.php.md):145 |  | insert | `email_id`, `exchange_attachment_id`, `filename`, `content_type`, `content_id`, `file_path`, `file_size`, `is_inline` |  |
| [api/messaging/send_attachment.php](/code/files/api/messaging/send_attachment.php.md):177 |  | delete |  |  |
| [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md):236 |  | insert | `email_id`, `filename`, `content_type`, `file_path`, `file_size`, `is_inline`, `created_datetime` |  |
| [api/self-service/reply_ticket.php](/code/files/api/self-service/reply_ticket.php.md):195 |  | insert | `email_id`, `filename`, `content_type`, `file_path`, `file_size`, `is_inline`, `created_datetime` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):2078 |  | update | `file_path` |  |
| [api/system/debug-tools/D002_delete_ticket.php](/code/files/api/system/debug-tools/D002_delete_ticket.php.md):375 |  | delete |  |  |
| [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md):1007 | `saveAttachment` | insert | `email_id`, `exchange_attachment_id`, `filename`, `content_type`, `content_id`, `file_path`, `file_size`, `is_inline` |  |
| [api/tickets/empty_trash.php](/code/files/api/tickets/empty_trash.php.md):73 |  | delete |  |  |
| [api/tickets/permanently_delete_ticket.php](/code/files/api/tickets/permanently_delete_ticket.php.md):79 |  | delete |  |  |
| [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md):889 | `saveSentAttachments` | insert | `email_id`, `filename`, `content_type`, `file_path`, `file_size`, `is_inline`, `created_datetime` |  |
| [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md):1065 | `saveChannelMediaAttachment` | insert | `email_id`, `exchange_attachment_id`, `filename`, `content_type`, `content_id`, `file_path`, `file_size`, `is_inline` |  |
| [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md):235 | `unmergeTicket` | delete |  |  |
| [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md):825 | `mergeAttachSnapshot` | insert | `email_id`, `filename`, `content_type`, `file_path`, `file_size`, `is_inline`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/messaging/media.php](/code/files/api/messaging/media.php.md) | L75 |  |
| [api/self-service/get_attachment.php](/code/files/api/self-service/get_attachment.php.md) | L42 |  |
| [api/self-service/get_ticket_detail.php](/code/files/api/self-service/get_ticket_detail.php.md) | L172 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L2074 |  |
| [api/system/debug-tools/D002_delete_ticket.php](/code/files/api/system/debug-tools/D002_delete_ticket.php.md) | L265, L310, L336, L366, L420 |  |
| [api/system/debug-tools/D012_ticket_attachments.php](/code/files/api/system/debug-tools/D012_ticket_attachments.php.md) | L473 |  |
| [api/system/search_status.php](/code/files/api/system/search_status.php.md) | L102 |  |
| [api/system/tika_settings.php](/code/files/api/system/tika_settings.php.md) | L97 |  |
| [api/tickets/empty_trash.php](/code/files/api/tickets/empty_trash.php.md) | L48 |  |
| [api/tickets/get_attachment.php](/code/files/api/tickets/get_attachment.php.md) | L34, L39 |  |
| [api/tickets/get_ticket_attachments.php](/code/files/api/tickets/get_ticket_attachments.php.md) | L37 |  |
| [api/tickets/permanently_delete_ticket.php](/code/files/api/tickets/permanently_delete_ticket.php.md) | L55 |  |
| [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md) | getAttachmentById L546, getAttachmentByCid L572 |  |
| [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) | integrationsTicketAttachments L736 |  |
| [includes/search/extract_queue.php](/code/files/includes/search/extract_queue.php.md) | extractQueueDrain L107, extractQueueDrain L172 |  |
| [includes/search/indexer.php](/code/files/includes/search/indexer.php.md) | searchIndexTicketAttachments L164 |  |
| [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md) | unmergeTicket L229 |  |


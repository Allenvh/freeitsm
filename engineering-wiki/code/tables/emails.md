# emails

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `exchange_message_id` | `VARCHAR(255) NULL` |  |
| `subject` | `VARCHAR(500) NULL` |  |
| `from_address` | `VARCHAR(255) NULL` | NULL = the sender has no email address at all — a portal requester who signs in through a directory and was never given a mailbox. Only ever NULL for portal-raised messages; anything that arrived BY email has a sender by definition. `from_name` identifies these people instead. |
| `from_name` | `VARCHAR(255) NULL` |  |
| `to_recipients` | `LONGTEXT NULL` |  |
| `cc_recipients` | `LONGTEXT NULL` |  |
| `received_datetime` | `DATETIME NULL` |  |
| `body_preview` | `LONGTEXT NULL` |  |
| `body_content` | `LONGTEXT NULL` |  |
| `body_type` | `VARCHAR(20) NULL` |  |
| `has_attachments` | `TINYINT(1) NULL DEFAULT 0` |  |
| `importance` | `VARCHAR(20) NULL` |  |
| `is_read` | `TINYINT(1) NULL DEFAULT 0` |  |
| `processed_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `ticket_created` | `TINYINT(1) NULL DEFAULT 0` |  |
| `ticket_id` | `INT NULL` |  |
| `department_id` | `INT NULL` |  |
| `ticket_type_id` | `INT NULL` |  |
| `assigned_analyst_id` | `INT NULL` |  |
| `status` | `VARCHAR(50) NULL DEFAULT 'New'` |  |
| `assigned_datetime` | `DATETIME NULL` |  |
| `is_initial` | `TINYINT(1) NULL DEFAULT 0` |  |
| `direction` | `VARCHAR(20) NULL DEFAULT 'Inbound'` |  |
| `mailbox_id` | `INT NULL` |  |
| `channel` | `VARCHAR(20) NOT NULL DEFAULT 'email'` | Which channel this message arrived/left on. 'email' (default) leaves every existing row and the email pipeline untouched; 'whatsapp' reuses this table. |
| `channel_id` | `INT NULL` | For channel messages: the messaging_channels row (which provider/number to reply from). NULL for email. |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/messaging/send_attachment.php](/code/files/api/messaging/send_attachment.php.md):110 |  | insert | `exchange_message_id`, `subject`, `from_address`, `from_name`, `to_recipients`, `received_datetime`, `body_content`, `body_type`, `has_attachments`, `ticket_id` |  |
| [api/messaging/send_attachment.php](/code/files/api/messaging/send_attachment.php.md):164 |  | update | `exchange_message_id` |  |
| [api/messaging/send_attachment.php](/code/files/api/messaging/send_attachment.php.md):178 |  | delete |  |  |
| [api/messaging/send_message.php](/code/files/api/messaging/send_message.php.md):102 |  | insert | `exchange_message_id`, `subject`, `from_address`, `from_name`, `to_recipients`, `received_datetime`, `body_content`, `body_type`, `ticket_id`, `is_initial` |  |
| [api/messaging/send_template.php](/code/files/api/messaging/send_template.php.md):79 |  | insert | `exchange_message_id`, `subject`, `from_address`, `from_name`, `to_recipients`, `received_datetime`, `body_content`, `body_type`, `ticket_id`, `is_initial` |  |
| [api/messaging/test_channel.php](/code/files/api/messaging/test_channel.php.md):225 | `simCleanup` | delete |  |  |
| [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md):204 |  | insert | `subject`, `from_address`, `from_name`, `to_recipients`, `received_datetime`, `body_preview`, `body_content`, `body_type`, `has_attachments`, `importance` |  |
| [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md):271 |  | update | `body_content`, `has_attachments` |  |
| [api/self-service/reply_ticket.php](/code/files/api/self-service/reply_ticket.php.md):132 |  | insert | `subject`, `from_address`, `from_name`, `to_recipients`, `received_datetime`, `body_preview`, `body_content`, `body_type`, `has_attachments`, `importance` |  |
| [api/self-service/reply_ticket.php](/code/files/api/self-service/reply_ticket.php.md):226 |  | update | `body_content`, `has_attachments` |  |
| [api/system/debug-tools/D002_delete_ticket.php](/code/files/api/system/debug-tools/D002_delete_ticket.php.md):376 |  | delete |  |  |
| [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md):1217 | `saveEmailToDatabase` | insert | `exchange_message_id`, `subject`, `from_address`, `from_name`, `to_recipients`, `cc_recipients`, `received_datetime`, `body_preview`, `body_content`, `body_type` |  |
| [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md):1353 | `saveEmailToDatabase` | update | `body_content`, `has_attachments` |  |
| [api/tickets/empty_trash.php](/code/files/api/tickets/empty_trash.php.md):74 |  | delete |  |  |
| [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md):194 |  | update | `is_read` |  |
| [api/tickets/permanently_delete_ticket.php](/code/files/api/tickets/permanently_delete_ticket.php.md):84 |  | delete |  |  |
| [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md):820 | `saveSentEmail` | insert | `subject`, `from_address`, `from_name`, `to_recipients`, `cc_recipients`, `received_datetime`, `body_content`, `body_type`, `ticket_id`, `is_initial` |  |
| [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md):926 | `saveSentAttachments` | update | `has_attachments` |  |
| [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md):935 | `saveSentAttachments` | update | `body_content` |  |
| [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md):323 | `catalogueCreateTicketFromSubmission` | insert | `subject`, `from_address`, `from_name`, `to_recipients`, `received_datetime`, `body_preview`, `body_content`, `body_type`, `has_attachments`, `importance` |  |
| [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md):189 | `ingestInboundMessage` | insert | `exchange_message_id`, `subject`, `from_address`, `from_name`, `to_recipients`, `received_datetime`, `body_content`, `body_type`, `has_attachments`, `is_read` |  |
| [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md):252 | `ingestInboundMessage` | update | `has_attachments` |  |
| [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md):254 | `ingestInboundMessage` | update | `body_content` |  |
| [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md):261 | `ingestInboundMessage` | update | `body_content` |  |
| [includes/services/tickets.php](/code/files/includes/services/tickets.php.md):253 | `TicketsService::createTicket` | insert | `mailbox_id`, `subject`, `from_address`, `from_name`, `to_recipients`, `received_datetime`, `body_preview`, `body_content`, `body_type`, `has_attachments` |  |
| [includes/template_email.php](/code/files/includes/template_email.php.md):750 | `templateSaveSentEmail` | insert | `subject`, `from_address`, `from_name`, `to_recipients`, `received_datetime`, `body_content`, `body_type`, `ticket_id`, `is_initial`, `direction` |  |
| [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md):210 | `unmergeTicket` | update | `ticket_id` |  |
| [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md):236 | `unmergeTicket` | delete |  |  |
| [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md):508 | `mergeOneTicket` | update | `ticket_id` |  |
| [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md):774 | `mergeAttachSnapshot` | insert | `subject`, `from_address`, `from_name`, `received_datetime`, `body_content`, `body_type`, `has_attachments`, `is_read`, `ticket_id`, `direction` |  |
| [includes/ticket_split.php](/code/files/includes/ticket_split.php.md):210 | `splitTicket` | update | `ticket_id` |  |
| [includes/ticket_split.php](/code/files/includes/ticket_split.php.md):326 | `splitLeaveMarker` | insert | `subject`, `from_address`, `from_name`, `received_datetime`, `body_content`, `body_type`, `has_attachments`, `is_read`, `ticket_id`, `direction` |  |
| [includes/ticket_split.php](/code/files/includes/ticket_split.php.md):454 | `undoSplit` | update | `ticket_id` |  |
| [includes/ticket_split.php](/code/files/includes/ticket_split.php.md):458 | `undoSplit` | delete |  |  |
| [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md):280 | `webchatInsertInbound` | insert | `exchange_message_id`, `subject`, `from_address`, `from_name`, `to_recipients`, `received_datetime`, `body_content`, `body_type`, `has_attachments`, `is_read` |  |
| [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md):320 | `webchatInsertOutbound` | insert | `exchange_message_id`, `subject`, `from_address`, `from_name`, `to_recipients`, `received_datetime`, `body_content`, `body_type`, `has_attachments`, `is_read` |  |
| [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md):560 | `webchatPromoteToTicket` | update | `has_attachments` |  |
| [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md):2772 | `WorkflowEngine::action_create_ticket` | insert | `subject`, `from_address`, `from_name`, `to_recipients`, `received_datetime`, `body_preview`, `body_content`, `body_type`, `has_attachments`, `importance` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/integrations/escalate_ticket.php](/code/files/api/integrations/escalate_ticket.php.md) | L108 |  |
| [api/messaging/media.php](/code/files/api/messaging/media.php.md) | L75 |  |
| [api/messaging/send_attachment.php](/code/files/api/messaging/send_attachment.php.md) | L70 |  |
| [api/messaging/send_message.php](/code/files/api/messaging/send_message.php.md) | L50 |  |
| [api/messaging/send_template.php](/code/files/api/messaging/send_template.php.md) | L41 |  |
| [api/problem-management/ai_root_cause.php](/code/files/api/problem-management/ai_root_cause.php.md) | L35 |  |
| [api/self-service/get_attachment.php](/code/files/api/self-service/get_attachment.php.md) | L42 |  |
| [api/self-service/get_team_tickets.php](/code/files/api/self-service/get_team_tickets.php.md) | L101 |  |
| [api/self-service/get_ticket_detail.php](/code/files/api/self-service/get_ticket_detail.php.md) | L141 |  |
| [api/self-service/get_tickets.php](/code/files/api/self-service/get_tickets.php.md) | L30 |  |
| [api/self-service/reply_ticket.php](/code/files/api/self-service/reply_ticket.php.md) | L114 |  |
| [api/system/debug-tools/D002_delete_ticket.php](/code/files/api/system/debug-tools/D002_delete_ticket.php.md) | L238, L290, L337 |  |
| [api/system/debug-tools/D012_ticket_attachments.php](/code/files/api/system/debug-tools/D012_ticket_attachments.php.md) | L473 |  |
| [api/system/search_status.php](/code/files/api/system/search_status.php.md) | L102 |  |
| [api/tickets/ai_cleanup_reply.php](/code/files/api/tickets/ai_cleanup_reply.php.md) | L146 |  |
| [api/tickets/ai_merge_summary.php](/code/files/api/tickets/ai_merge_summary.php.md) | L106 |  |
| [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) | saveEmailToDatabase L1085, oversizeMeasure L1461 |  |
| [api/tickets/empty_trash.php](/code/files/api/tickets/empty_trash.php.md) | L48, L73 |  |
| [api/tickets/get_attachment.php](/code/files/api/tickets/get_attachment.php.md) | L61 |  |
| [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md) | L113, L118 |  |
| [api/tickets/get_emails.php](/code/files/api/tickets/get_emails.php.md) | L56 |  |
| [api/tickets/get_inbox_activity.php](/code/files/api/tickets/get_inbox_activity.php.md) | L41 |  |
| [api/tickets/get_ticket_attachments.php](/code/files/api/tickets/get_ticket_attachments.php.md) | L37 |  |
| [api/tickets/get_ticket_thread.php](/code/files/api/tickets/get_ticket_thread.php.md) | L39, L150 |  |
| [api/tickets/get_triage.php](/code/files/api/tickets/get_triage.php.md) | L38 |  |
| [api/tickets/permanently_delete_ticket.php](/code/files/api/tickets/permanently_delete_ticket.php.md) | L55, L79 |  |
| [api/tickets/resolve_triage.php](/code/files/api/tickets/resolve_triage.php.md) | L125, L146 |  |
| [api/tickets/search_content.php](/code/files/api/tickets/search_content.php.md) | L85 |  |
| [api/tickets/search_tickets.php](/code/files/api/tickets/search_tickets.php.md) | L67 |  |
| [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md) | getMailboxForTicket L211, getAttachmentById L546, getAttachmentByCid L572, buildFullEmailBody L705 |  |
| [api/tickets/split_ticket_preview.php](/code/files/api/tickets/split_ticket_preview.php.md) | L50 |  |
| [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md) | apiTicketsGet L258, apiTicketThreadList L374 |  |
| [api/webchat/poll.php](/code/files/api/webchat/poll.php.md) | L53 |  |
| [includes/csat.php](/code/files/includes/csat.php.md) | csatTicketChannel L131, csatPendingRequestForChat L227 |  |
| [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) | integrationsTicketAttachments L736 |  |
| [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md) | gapBulkRichness L378 |  |
| [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md) | writeupTicketBundle L252, writeupEmbeddingText L378 |  |
| [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) | ingestInboundMessage L46, findOpenChannelTicket L308, messagingTelegramLinkPhone L709 |  |
| [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md) | messagingBuildTranscript L384 |  |
| [includes/search/extract_queue.php](/code/files/includes/search/extract_queue.php.md) | extractQueueDrain L172 |  |
| [includes/search/indexer.php](/code/files/includes/search/indexer.php.md) | searchIndexTicket L92, searchIndexTicketAttachments L164 |  |
| [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md) | sla_emit_workflow_event L170 |  |
| [includes/template_email.php](/code/files/includes/template_email.php.md) | templateGetMailboxForTicket L482 |  |
| [includes/ticket_ai.php](/code/files/includes/ticket_ai.php.md) | ticketAiTranscript L132, ticketAiTranscript L144, ticketAiMessagesSince L210, ticketAiMessagesSince L216 |  |
| [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md) | unmergeTicket L198, unmergeTicket L270, mergeOneTicket L504, mergeBuildSnapshotHtml L686 |  |
| [includes/ticket_split.php](/code/files/includes/ticket_split.php.md) | splitMessagesFrom L51, splitMessagesFrom L67, splitMovableCount L108, splitMessagesByIds L136, undoSplit L429, undoSplit L435 |  |
| [includes/webchat/public.php](/code/files/includes/webchat/public.php.md) | webchatRateLimitSend L184 |  |
| [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md) | webchatMirrorAgentReplies L509 |  |


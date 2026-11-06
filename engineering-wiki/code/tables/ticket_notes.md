# ticket_notes

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `ticket_id` | `INT NOT NULL` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `note_text` | `LONGTEXT NOT NULL` |  |
| `is_internal` | `TINYINT(1) NULL DEFAULT 1` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/integrations/send_attachment.php](/code/files/api/integrations/send_attachment.php.md):110 |  | insert | `ticket_id`, `analyst_id`, `note_text`, `is_internal`, `created_datetime` |  |
| [api/messaging/ai_summary.php](/code/files/api/messaging/ai_summary.php.md):71 |  | insert | `ticket_id`, `analyst_id`, `note_text`, `is_internal`, `created_datetime` |  |
| [api/system/debug-tools/D002_delete_ticket.php](/code/files/api/system/debug-tools/D002_delete_ticket.php.md):377 |  | delete |  |  |
| [api/tickets/empty_trash.php](/code/files/api/tickets/empty_trash.php.md):75 |  | delete |  |  |
| [api/tickets/permanently_delete_ticket.php](/code/files/api/tickets/permanently_delete_ticket.php.md):87 |  | delete |  |  |
| [api/tickets/save_merge_summary.php](/code/files/api/tickets/save_merge_summary.php.md):51 |  | insert | `ticket_id`, `analyst_id`, `note_text`, `is_internal`, `created_datetime` |  |
| [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md):1374 | `integrationsApplyCommentEvent` | insert | `ticket_id`, `analyst_id`, `note_text`, `is_internal`, `created_datetime` |  |
| [includes/services/checklists.php](/code/files/includes/services/checklists.php.md):285 | `ChecklistsService::writeClosureNote` | insert | `ticket_id`, `analyst_id`, `note_text`, `is_internal`, `created_datetime` |  |
| [includes/services/mandatory_fields.php](/code/files/includes/services/mandatory_fields.php.md):243 | `MandatoryFieldsService::writeNote` | insert | `ticket_id`, `analyst_id`, `note_text`, `is_internal`, `created_datetime` |  |
| [includes/services/tickets.php](/code/files/includes/services/tickets.php.md):904 | `TicketsService::createNote` | insert | `ticket_id`, `analyst_id`, `note_text`, `is_internal`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/self-service/get_document.php](/code/files/api/self-service/get_document.php.md) | L64 |  |
| [api/self-service/get_ticket_detail.php](/code/files/api/self-service/get_ticket_detail.php.md) | L201 |  |
| [api/system/debug-tools/D002_delete_ticket.php](/code/files/api/system/debug-tools/D002_delete_ticket.php.md) | L338 |  |
| [api/system/debug-tools/D012_ticket_attachments.php](/code/files/api/system/debug-tools/D012_ticket_attachments.php.md) | L507, L951, L993 |  |
| [api/tickets/ai_merge_summary.php](/code/files/api/tickets/ai_merge_summary.php.md) | L117 |  |
| [api/tickets/empty_trash.php](/code/files/api/tickets/empty_trash.php.md) | L61 |  |
| [api/tickets/get_notes.php](/code/files/api/tickets/get_notes.php.md) | L37 |  |
| [api/tickets/permanently_delete_ticket.php](/code/files/api/tickets/permanently_delete_ticket.php.md) | L65 |  |
| [api/tickets/ticket_checklists.php](/code/files/api/tickets/ticket_checklists.php.md) | L155 |  |
| [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md) | apiTicketNotesList L335 |  |
| [includes/documents.php](/code/files/includes/documents.php.md) | documentEntityRegistry L120 |  |
| [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md) | gapBulkRichness L388 |  |
| [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md) | writeupTicketBundle L262 |  |
| [includes/search/indexer.php](/code/files/includes/search/indexer.php.md) | searchIndexTicket L114 |  |
| [includes/ticket_ai.php](/code/files/includes/ticket_ai.php.md) | ticketAiTranscript L151 |  |
| [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md) | mergeBuildSnapshotHtml L696 |  |


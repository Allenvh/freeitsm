# analystCanAccessTicket()

May this analyst access this *ticket* (by its owning company)?

The isolation gate for detail-by-id and mutation-by-id endpoints: a ticket
belongs to a company, and an analyst may only see/act on a ticket whose
company is in their accessible set (all-access analysts see every company).
A ticket with tenant_id NULL is treated as Default-owned (an un-routed ticket
that surfaces under Default), so access follows Default.

Deliberately defensive:
  - Single-company install → always true (nothing to isolate).
  - Unknown ticket id → false (don't reveal another company's row).
  - Part-migrated table (no tenant_id column) → true, so it never blocks an
    install mid-rollout (isMultiTenant is false there anyway).

```php
function analystCanAccessTicket(PDO $conn, int $analystId, $ticketId): bool
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 270.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` |  |  | the ticket id, any form; cast to int, and 0 or less is false |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 279 | `analystCanAccessTicket` | read [tickets](/code/tables/tickets.md) |  |

## Calls

[analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [tenancyDegradeAllowed()](/code/functions/tenancyDegradeAllowed.md)

## Called from

- [api/integrations/escalate_ticket.php](/code/files/api/integrations/escalate_ticket.php.md)
- [api/integrations/send_attachment.php](/code/files/api/integrations/send_attachment.php.md)
- [api/knowledge/ai_chat.php](/code/files/api/knowledge/ai_chat.php.md)
- [api/knowledge/writeup_save.php](/code/files/api/knowledge/writeup_save.php.md)
- [api/knowledge/writeup_stream.php](/code/files/api/knowledge/writeup_stream.php.md)
- [api/messaging/ai_suggest_reply.php](/code/files/api/messaging/ai_suggest_reply.php.md)
- [api/messaging/ai_summary.php](/code/files/api/messaging/ai_summary.php.md)
- [api/messaging/send_attachment.php](/code/files/api/messaging/send_attachment.php.md)
- [api/messaging/send_message.php](/code/files/api/messaging/send_message.php.md)
- [api/messaging/send_template.php](/code/files/api/messaging/send_template.php.md)
- [api/self-service/get_recording.php](/code/files/api/self-service/get_recording.php.md)
- [api/system/debug-tools/D012_ticket_attachments.php](/code/files/api/system/debug-tools/D012_ticket_attachments.php.md)
- [api/tickets/ai_cleanup_reply.php](/code/files/api/tickets/ai_cleanup_reply.php.md)
- [api/tickets/ai_merge_summary.php](/code/files/api/tickets/ai_merge_summary.php.md)
- [api/tickets/ai_read.php](/code/files/api/tickets/ai_read.php.md)
- [api/tickets/ai_summary.php](/code/files/api/tickets/ai_summary.php.md)
- [api/tickets/delete_ticket_asset.php](/code/files/api/tickets/delete_ticket_asset.php.md)
- [api/tickets/delete_ticket_cmdb_object.php](/code/files/api/tickets/delete_ticket_cmdb_object.php.md)
- [api/tickets/delete_ticket_task.php](/code/files/api/tickets/delete_ticket_task.php.md)
- [api/tickets/get_attachment.php](/code/files/api/tickets/get_attachment.php.md)
- [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md)
- [api/tickets/get_notes.php](/code/files/api/tickets/get_notes.php.md)
- [api/tickets/get_ticket_assets.php](/code/files/api/tickets/get_ticket_assets.php.md)
- [api/tickets/get_ticket_attachments.php](/code/files/api/tickets/get_ticket_attachments.php.md)
- [api/tickets/get_ticket_audit.php](/code/files/api/tickets/get_ticket_audit.php.md)
- [api/tickets/get_ticket_cmdb_objects.php](/code/files/api/tickets/get_ticket_cmdb_objects.php.md)
- [api/tickets/get_ticket_sla.php](/code/files/api/tickets/get_ticket_sla.php.md)
- [api/tickets/get_ticket_tasks.php](/code/files/api/tickets/get_ticket_tasks.php.md)
- [api/tickets/get_ticket_thread.php](/code/files/api/tickets/get_ticket_thread.php.md)
- [api/tickets/get_tickets_sla_batch.php](/code/files/api/tickets/get_tickets_sla_batch.php.md)
- [api/tickets/get_time_entries.php](/code/files/api/tickets/get_time_entries.php.md)
- [api/tickets/log_ticket_audit.php](/code/files/api/tickets/log_ticket_audit.php.md)
- [api/tickets/move_ticket_to_company.php](/code/files/api/tickets/move_ticket_to_company.php.md)
- [api/tickets/permanently_delete_ticket.php](/code/files/api/tickets/permanently_delete_ticket.php.md)
- [api/tickets/render_reply_template.php](/code/files/api/tickets/render_reply_template.php.md)
- [api/tickets/request_csat.php](/code/files/api/tickets/request_csat.php.md)
- [api/tickets/save_merge_summary.php](/code/files/api/tickets/save_merge_summary.php.md)
- [api/tickets/save_ticket_asset.php](/code/files/api/tickets/save_ticket_asset.php.md)
- [api/tickets/save_ticket_cmdb_object.php](/code/files/api/tickets/save_ticket_cmdb_object.php.md)
- [api/tickets/save_ticket_subject.php](/code/files/api/tickets/save_ticket_subject.php.md)
- [api/tickets/save_ticket_task.php](/code/files/api/tickets/save_ticket_task.php.md)
- [api/tickets/search_linkable_assets.php](/code/files/api/tickets/search_linkable_assets.php.md)
- [api/tickets/search_linkable_tasks.php](/code/files/api/tickets/search_linkable_tasks.php.md)
- [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md)
- [api/tickets/snooze_ticket.php](/code/files/api/tickets/snooze_ticket.php.md)
- [api/tickets/split_ticket_preview.php](/code/files/api/tickets/split_ticket_preview.php.md)
- [api/tickets/ticket_checklists.php](/code/files/api/tickets/ticket_checklists.php.md) - in [checklistTicketGate()](/code/functions/checklistTicketGate.md)
- [api/tickets/ticket_presence.php](/code/files/api/tickets/ticket_presence.php.md)
- [api/tickets/wake_ticket.php](/code/files/api/tickets/wake_ticket.php.md)
- [includes/documents.php](/code/files/includes/documents.php.md) - in [documentEntityRegistry()](/code/functions/documentEntityRegistry.md)
- [includes/domains/links.php](/code/files/includes/domains/links.php.md) - in [domainLinkTargetOk()](/code/functions/domainLinkTargetOk.md)
- [includes/people.php](/code/files/includes/people.php.md) - in [peopleForms()](/code/functions/peopleForms.md)
- [includes/projects/links.php](/code/files/includes/projects/links.php.md) - in [projectLinkTargetOk()](/code/functions/projectLinkTargetOk.md)
- [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) - in [recentTrailLabelsForType()](/code/functions/recentTrailLabelsForType.md)
- [includes/record_preview.php](/code/files/includes/record_preview.php.md) - in [recordPreviewTicket()](/code/functions/recordPreviewTicket.md)
- [includes/ticket_links.php](/code/files/includes/ticket_links.php.md) - in [ticketLinkCreate()](/code/functions/ticketLinkCreate.md), [ticketLinkRemove()](/code/functions/ticketLinkRemove.md), [ticketLinkableList()](/code/functions/ticketLinkableList.md)


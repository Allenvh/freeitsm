# api/tickets/permanently_delete_ticket.php

> API endpoint - 126 lines

API Endpoint: PERMANENTLY delete a ticket and all associated data. Irreversible — only allowed on a ticket already in the trash. Reached from the Trash view ("Delete permanently" / "Empty trash").

Children are removed in foreign-key-safe order inside a transaction. Several
child tables have FKs with NO "ON DELETE CASCADE" (email_attachments→emails,
ticket_notes/ticket_audit/ticket_time_entries→tickets), so they must be
deleted explicitly and in the right order — otherwise a ticket whose emails
carry attachments fails with "1451 Cannot delete or update a parent row".
The remaining children (ticket_recordings, ticket_csat_responses,
ticket_cmdb_objects, ticket_assets, sla_notifications_sent, tasks) have
CASCADE / SET NULL rules and clean themselves up.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/permanently_delete_ticket.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 45 |  | read [tickets](/code/tables/tickets.md) |  |
| 55 |  | read [email_attachments](/code/tables/email_attachments.md), read [emails](/code/tables/emails.md) |  |
| 65 |  | read [ticket_notes](/code/tables/ticket_notes.md) |  |
| 79 |  | **delete** [email_attachments](/code/tables/email_attachments.md), read [emails](/code/tables/emails.md) |  |
| 84 |  | **delete** [emails](/code/tables/emails.md) |  |
| 87 |  | **delete** [ticket_notes](/code/tables/ticket_notes.md) |  |
| 90 |  | **delete** [ticket_audit](/code/tables/ticket_audit.md) |  |
| 93 |  | **delete** [ticket_time_entries](/code/tables/ticket_time_entries.md) |  |
| 98 |  | **delete** [ticket_checklist_items](/code/tables/ticket_checklist_items.md), read [ticket_checklists](/code/tables/ticket_checklists.md) |  |
| 101 |  | **delete** [ticket_checklists](/code/tables/ticket_checklists.md) |  |
| 104 |  | **delete** [tickets](/code/tables/tickets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/documents.php](/code/files/includes/documents.php.md)

**Calls:** [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [documentsDetachParent()](/code/functions/documentsDetachParent.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


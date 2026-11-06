# api/tickets/empty_trash.php

> API endpoint - 94 lines

API Endpoint: Empty the trash — permanently delete every trashed ticket in the analyst's active company (and all their emails/attachments/notes/etc.). Irreversible. Scoped to the active company so it matches the Trash view.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/empty_trash.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 36 |  | read [tickets](/code/tables/tickets.md) |  |
| 48 |  | read [email_attachments](/code/tables/email_attachments.md), read [emails](/code/tables/emails.md) |  |
| 61 |  | read [ticket_notes](/code/tables/ticket_notes.md) |  |
| 73 |  | **delete** [email_attachments](/code/tables/email_attachments.md), read [emails](/code/tables/emails.md) |  |
| 74 |  | **delete** [emails](/code/tables/emails.md) |  |
| 75 |  | **delete** [ticket_notes](/code/tables/ticket_notes.md) |  |
| 76 |  | **delete** [ticket_audit](/code/tables/ticket_audit.md) |  |
| 77 |  | **delete** [ticket_time_entries](/code/tables/ticket_time_entries.md) |  |
| 78 |  | **delete** [tickets](/code/tables/tickets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/documents.php](/code/files/includes/documents.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [documentsDetachParent()](/code/functions/documentsDetachParent.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ticketTenantFilter()](/code/functions/ticketTenantFilter.md)


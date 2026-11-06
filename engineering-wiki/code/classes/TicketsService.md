# TicketsService

> Class in [includes/services/tickets.php](/code/files/includes/services/tickets.php.md), line 46

## Methods

| Method | What it does |
|---|---|
| [createTicket()](#createticket) | Create a ticket (with its initial email row + audit + ticket.created). `$tenantId` is the adapter-resolved acting company. `$defaultAnalystId` is the assignee when the caller sends none (UI: the creator; API: null). Returns the new ticket id. |
| [updateTicket()](#updateticket) | Apply a partial update to a ticket. `$writeAudit` toggles the server-side audit trail (API: true; UI: false — it audits client-side). Returns void; the adapter reloads for its response. |
| [deleteTicket()](#deleteticket) | Move a ticket to the trash. `$writeAudit` -> the 'Trash' audit row (UI: true; API: false). |
| [restoreTicket()](#restoreticket) | Restore a ticket from the trash. `$writeAudit` -> the 'Trash' audit row. |
| [eventTicket()](#eventticket) | The canonical ticket workflow payload, built from a loaded (joined) row. |
| [createNote()](#createnote) | Add a note. Returns the note id; touches the ticket's updated_datetime. |
| [createTimeEntry()](#createtimeentry) | Log time on a ticket. Returns the entry id. |
| [updateTimeEntry()](#updatetimeentry) | Edit a time entry (UI-only; only the owning analyst may edit). |
| [deleteTimeEntry()](#deletetimeentry) | Soft-delete a time entry. `$ticketId` scopes it (API route); pass null to resolve the ticket from the entry (the UI passes only the entry id). `$restrictToOwner` enforces the UI's "only your own entry" rule. |
| [rowAfterUpdates()](#rowafterupdates) | The ticket row as it will be once an UPDATE built by `updateTicket()` lands. |
| [loadTicket()](#loadticket) | Load the joined ticket row, enforcing the actor's company scope; 404 otherwise. |
| [canAccessTicket()](#canaccessticket) | May the actor access this ticket (by its company)? Mirror of apiKeyCanAccessTenantRow. |
| [auditWrite()](#auditwrite) | Records one field change in the ticket's audit trail. |
| [resolveStatus()](#resolvestatus) | Resolve a status by name or id. Returns [id, name, is_closed] or null. |
| [resolvePriority()](#resolvepriority) | Resolve a priority by name or id. Empty string/null id = clear. |
| [teamName()](#teamname) | A team's name for the audit trail, or null. Never throws — this is a label. |
| [categoryEffectiveTypeId()](#categoryeffectivetypeid) | The ticket type a category is bound to, taken from its top-level category; null when unbound or it cannot be worked out. |
| [validateLookupId()](#validatelookupid) | The name of a row in a lookup table, or null for no id; throws for an unknown id. |
| [parseDate()](#parsedate) | Parse a date to UTC 'Y-m-d H:i:s' (mirrors apiParseDate; 400 on garbage). |

### createTicket

*public static* - line 76

Create a ticket (with its initial email row + audit + ticket.created). `$tenantId` is the adapter-resolved acting company. `$defaultAnalystId` is the assignee when the caller sends none (UI: the creator; API: null). Returns the new ticket id.

```php
function createTicket(
    PDO $conn,
    ActorContext $ctx,
    int $tenantId,
    array $in,
    ?int $defaultAnalystId,
    string $creationNote
): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | subject (required), description, requester_email (required unless user_id), requester_name, user_id (a requester the caller has already authorised), status_id/status, priority_id/priority, department_id, ticket_type_id, origin_id, category_id, assigned_analyst_id, assigned_team_id, mailbox_id, sensitivity |
| `$defaultAnalystId` | `?int` |  | the assignee when `$in` names none (UI: the creator; API: null for unassigned) |
| `$creationNote` | `string` |  | the text of the 'Ticket Created' audit row, e.g. "Created via API (key: X)" |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 118 | `TicketsService::createTicket` | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 123 | `TicketsService::createTicket` | read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 166 | `TicketsService::createTicket` | read [analysts](/code/tables/analysts.md) |  |
| 173 | `TicketsService::createTicket` | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 193 | `TicketsService::createTicket` | read [users](/code/tables/users.md) |  |
| 205 | `TicketsService::createTicket` | read [users](/code/tables/users.md) |  |
| 223 | `TicketsService::createTicket` | **insert** [users](/code/tables/users.md) (`email`, `display_name`, `tenant_id`, `created_at`) |  |
| 237 | `TicketsService::createTicket` | **insert** [tickets](/code/tables/tickets.md) (`tenant_id`, `ticket_number`, `subject`, `status_id`, `priority_id`, `department_id`, `ticket_type_id`, `category_id`, `origin_id`, `assigned_team_id`, `assigned_analyst_id`, `owner_id`) |  |
| 253 | `TicketsService::createTicket` | **insert** [emails](/code/tables/emails.md) (`mailbox_id`, `subject`, `from_address`, `from_name`, `to_recipients`, `received_datetime`, `body_preview`, `body_content`, `body_type`, `has_attachments`, `importance`, `is_read`) |  |

**Called from:** [api/tickets/create_ticket.php](/code/files/api/tickets/create_ticket.php.md), [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md), [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md)

### updateTicket

*public static* - line 298

Apply a partial update to a ticket. `$writeAudit` toggles the server-side audit trail (API: true; UI: false — it audits client-side). Returns void; the adapter reloads for its response.

```php
function updateTicket(
    PDO $conn,
    ActorContext $ctx,
    int $ticketId,
    array $in,
    bool $writeAudit
): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | only the fields to change: subject, status/priority (by id or name), the lookup ids, assigned_analyst_id, assigned_team_id, company_id, sensitivity, closed_message, work_start_at, work_end_at, work_all_day |
| `$writeAudit` | `bool` |  | true writes the ticket_audit rows here (API); false when the caller audits itself (UI) |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 471 | `TicketsService::updateTicket` | read [teams](/code/tables/teams.md) |  |
| 499 | `TicketsService::updateTicket` | read [analysts](/code/tables/analysts.md) |  |
| 648 | `TicketsService::updateTicket` | **update** [tickets](/code/tables/tickets.md) |  |
| 740 | `TicketsService::updateTicket` | read [tickets](/code/tables/tickets.md), read [users](/code/tables/users.md) |  |

**Called from:** [api/self-service/close_ticket.php](/code/files/api/self-service/close_ticket.php.md), [api/tickets/assign_ticket.php](/code/files/api/tickets/assign_ticket.php.md), [api/tickets/bulk_update_tickets.php](/code/files/api/tickets/bulk_update_tickets.php.md), [api/tickets/schedule_ticket.php](/code/files/api/tickets/schedule_ticket.php.md), [api/tickets/update_ticket_owner.php](/code/files/api/tickets/update_ticket_owner.php.md), [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md), [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md)

### deleteTicket

*public static* - line 814

Move a ticket to the trash. `$writeAudit` -> the 'Trash' audit row (UI: true; API: false).

```php
function deleteTicket(PDO $conn, ActorContext $ctx, int $ticketId, bool $writeAudit): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$writeAudit` | `bool` |  | true writes the 'Trash' audit row (UI); false for the API |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 820 | `TicketsService::deleteTicket` | **update** [tickets](/code/tables/tickets.md) (`deleted_datetime`, `deleted_by`) |  |

**Called from:** [api/tickets/bulk_delete_tickets.php](/code/files/api/tickets/bulk_delete_tickets.php.md), [api/tickets/delete_ticket.php](/code/files/api/tickets/delete_ticket.php.md), [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md), [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md), [includes/ticket_split.php](/code/files/includes/ticket_split.php.md)

### restoreTicket

*public static* - line 836

Restore a ticket from the trash. `$writeAudit` -> the 'Trash' audit row.

```php
function restoreTicket(PDO $conn, ActorContext $ctx, int $ticketId, bool $writeAudit): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$writeAudit` | `bool` |  | true writes the 'Trash' audit row (UI); false for the API |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 842 | `TicketsService::restoreTicket` | **update** [tickets](/code/tables/tickets.md) (`deleted_datetime`, `deleted_by`) |  |

**Called from:** [api/tickets/restore_ticket.php](/code/files/api/tickets/restore_ticket.php.md), [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md)

### eventTicket

*private static* - line 857

The canonical ticket workflow payload, built from a loaded (joined) row.

```php
function eventTicket(array $r): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$r` | `array` |  | the ticket row, as loaded by `loadTicket()` or `rowAfterUpdates()` |

### createNote

*public static* - line 883

Add a note. Returns the note id; touches the ticket's updated_datetime.

```php
function createNote(PDO $conn, ActorContext $ctx, int $ticketId, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | text (required), is_internal (default true; false shares it with the requester and may email them) |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 904 | `TicketsService::createNote` | **insert** [ticket_notes](/code/tables/ticket_notes.md) (`ticket_id`, `analyst_id`, `note_text`, `is_internal`, `created_datetime`) |  |
| 907 | `TicketsService::createNote` | **update** [tickets](/code/tables/tickets.md) (`updated_datetime`) |  |

**Called from:** [api/tickets/save_note.php](/code/files/api/tickets/save_note.php.md), [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md)

### createTimeEntry

*public static* - line 970

Log time on a ticket. Returns the entry id.

```php
function createTimeEntry(PDO $conn, ActorContext $ctx, int $ticketId, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | minutes (required, above 0), notes, entry_at (ISO 8601; default now in UTC) |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 982 | `TicketsService::createTimeEntry` | **insert** [ticket_time_entries](/code/tables/ticket_time_entries.md) (`ticket_id`, `analyst_id`, `notes`, `time_spent_minutes`, `entry_datetime`) |  |

**Called from:** [api/tickets/save_time_entry.php](/code/files/api/tickets/save_time_entry.php.md), [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md)

### updateTimeEntry

*public static* - line 994

Edit a time entry (UI-only; only the owning analyst may edit).

```php
function updateTimeEntry(PDO $conn, ActorContext $ctx, int $entryId, int $ticketId, array $in): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$entryId` | `int` |  | the time entry (ticket_time_entries.id) to edit; must be the actor's own |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | minutes (required, above 0), notes, entry_at (ISO 8601; default now in UTC) |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1006 | `TicketsService::updateTimeEntry` | read [ticket_time_entries](/code/tables/ticket_time_entries.md) |  |
| 1015 | `TicketsService::updateTimeEntry` | **update** [ticket_time_entries](/code/tables/ticket_time_entries.md) (`notes`, `time_spent_minutes`, `entry_datetime`) |  |

**Called from:** [api/tickets/save_time_entry.php](/code/files/api/tickets/save_time_entry.php.md)

### deleteTimeEntry

*public static* - line 1027

Soft-delete a time entry. `$ticketId` scopes it (API route); pass null to resolve the ticket from the entry (the UI passes only the entry id). `$restrictToOwner` enforces the UI's "only your own entry" rule.

```php
function deleteTimeEntry(
    PDO $conn,
    ActorContext $ctx,
    int $entryId,
    ?int $ticketId,
    bool $restrictToOwner
): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$entryId` | `int` |  | the time entry (ticket_time_entries.id) to remove |
| `$ticketId` | `?int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$restrictToOwner` | `bool` |  | true allows only the analyst who logged it (UI); false allows any entry on a reachable ticket (API) |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1031 | `TicketsService::deleteTimeEntry` | read [ticket_time_entries](/code/tables/ticket_time_entries.md) |  |
| 1038 | `TicketsService::deleteTimeEntry` | read [ticket_time_entries](/code/tables/ticket_time_entries.md) |  |
| 1051 | `TicketsService::deleteTimeEntry` | **update** [ticket_time_entries](/code/tables/ticket_time_entries.md) (`is_active`) |  |

**Called from:** [api/tickets/delete_time_entry.php](/code/files/api/tickets/delete_time_entry.php.md), [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md)

### rowAfterUpdates

*private static* - line 1071

The ticket row as it will be once an UPDATE built by `updateTicket()` lands.

Read back out of the SET list rather than re-derived from the input, so it
includes what the service decides on its own - the category a type change
clears, the owner written alongside the analyst - and cannot drift from what
is actually written. Entries with no placeholder (closed_datetime =
`UTC_TIMESTAMP()`) consume no argument.

```php
function rowAfterUpdates(array $current, array $updates, array $args): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$current` | `array` |  | the ticket row before the update, as loaded by `loadTicket()` |
| `$updates` | `array` |  | "column = ?" / "column = <expr>" fragments |
| `$args` | `array` |  | the placeholder values, in order |

### loadTicket

*public static* - line 1097

Load the joined ticket row, enforcing the actor's company scope; 404 otherwise.

PUBLIC rather than private since #912: the merge engine (includes/ticket_merge.php)
has to check every ticket in a merge before moving a single row, and it must use
THIS scope rule rather than its own copy. A second implementation of "may this
actor see this ticket" is exactly the duplication that goes wrong silently —
nothing breaks when the copies drift, one of them just quietly shows more.
Read-only and side-effect free, so widening it changes no behaviour.

```php
function loadTicket(PDO $conn, ActorContext $ctx, int $ticketId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1100 | `TicketsService::loadTicket` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md), read [ticket_types](/code/tables/ticket_types.md), read [ticket_origins](/code/tables/ticket_origins.md), read [departments](/code/tables/departments.md), read [analysts](/code/tables/analysts.md), read [tenants](/code/tables/tenants.md) |  |

**Called from:** [api/tickets/get_mandatory_fields_check.php](/code/files/api/tickets/get_mandatory_fields_check.php.md), [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md), [includes/ticket_split.php](/code/files/includes/ticket_split.php.md)

### canAccessTicket

*private static* - line 1133

May the actor access this ticket (by its company)? Mirror of apiKeyCanAccessTenantRow.

```php
function canAccessTicket(PDO $conn, ActorContext $ctx, int $ticketId): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1138 | `TicketsService::canAccessTicket` | read [tickets](/code/tables/tickets.md) |  |

### auditWrite

*private static* - line 1158

Records one field change in the ticket's audit trail.

```php
function auditWrite(
    PDO $conn,
    int $ticketId,
    int $analystId,
    string $field,
    ?string $old,
    ?string $new
): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$field` | `string` |  | the audit field label, e.g. 'Status', 'Team', 'Trash', 'Ticket Created' |
| `$old` | `?string` |  | the value before, or null |
| `$new` | `?string` |  | the value after, or null |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1161 | `TicketsService::auditWrite` | **insert** [ticket_audit](/code/tables/ticket_audit.md) (`ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |

### resolveStatus

*private static* - line 1183

Resolve a status by name or id. Returns [id, name, is_closed] or null.

```php
function resolveStatus(PDO $conn, array $in): ?array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the request: status_id wins over status (a name); neither gives null, unknown throws |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1186 | `TicketsService::resolveStatus` | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 1189 | `TicketsService::resolveStatus` | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |

### resolvePriority

*private static* - line 1206

Resolve a priority by name or id. Empty string/null id = clear.

```php
function resolvePriority(PDO $conn, array $in): ?array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the request: priority_id (null or '' clears) wins over priority (a name); neither gives null |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1212 | `TicketsService::resolvePriority` | read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 1215 | `TicketsService::resolvePriority` | read [ticket_priorities](/code/tables/ticket_priorities.md) |  |

### teamName

*private static* - line 1240

A team's name for the audit trail, or null. Never throws — this is a label.

```php
function teamName(PDO $conn, ?int $teamId): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$teamId` | `?int` |  | the team (teams.id); null or 0 gives null |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1244 | `TicketsService::teamName` | read [teams](/code/tables/teams.md) |  |

### categoryEffectiveTypeId

*private static* - line 1259

The ticket type a category is bound to, taken from its top-level category; null when unbound or it cannot be worked out.

```php
function categoryEffectiveTypeId(PDO $conn, int $categoryId): ?int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$categoryId` | `int` |  | the ticket category (ticket_categories.id) to start from |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1264 | `TicketsService::categoryEffectiveTypeId` | read [ticket_categories](/code/tables/ticket_categories.md) |  |

### validateLookupId

*private static* - line 1290

The name of a row in a lookup table, or null for no id; throws for an unknown id.

```php
function validateLookupId(PDO $conn, string $table, $id, string $label): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$table` | `string` |  | the lookup table, e.g. 'departments', 'ticket_types', 'teams' - a developer literal |
| `$id` |  |  | the id as sent; null or '' gives null |
| `$label` | `string` |  | what the lookup is called in the error message, e.g. 'ticket type' |

### parseDate

*private static* - line 1310

Parse a date to UTC 'Y-m-d H:i:s' (mirrors apiParseDate; 400 on garbage).

```php
function parseDate(string $value, string $field): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$value` | `string` |  | a date and time in any form DateTimeImmutable reads; with no zone it is read as UTC |
| `$field` | `string` |  | the field name, used in the error message |


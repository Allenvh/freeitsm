# MandatoryFieldsService

> Class in [includes/services/mandatory_fields.php](/code/files/includes/services/mandatory_fields.php.md), line 41

## Methods

| Method | What it does |
|---|---|
| [settings()](#settings) | The stored configuration for a company (null = install-wide). |
| [parseAddresses()](#parseaddresses) | Split and validate an address list. Invalid entries are dropped, so the saved list and the one actually mailed can never disagree. |
| [missing()](#missing) | The required fields this ticket has left empty. |
| [fieldAvailable()](#fieldavailable) | Can this field be filled in on a ticket belonging to this company? |
| [assertClosureAllowed()](#assertclosureallowed) | Refuse the close under 'block'. Call BEFORE anything is written: thrown after the update, the ticket would already be closed and the caller would report a failure the database disagrees with. |
| [afterClosure()](#afterclosure) | After a close that went ahead with fields empty: write the note and send the email, as configured. Never throws - a failed note or email must not turn a close that happened into an error. |
| [writeNote()](#writenote) | An internal note from an analyst, or a 'Workflow Note' audit entry when nobody is acting. ticket_notes.analyst_id is NOT NULL with a foreign key to analysts, so a workflow (actor 0) cannot write there - which is why the workflow engine's own "add note" action writes to ticket_audit with a NULL analyst, and this follows it. UTC at rest (GH #126). |
| [closedByLine()](#closedbyline) | "Closed by Jo Bloggs via the API." / "Closed by a workflow." |
| [notify()](#notify) | Emails the chosen people that a ticket was closed with mandatory fields empty, listing them. |

### settings

*public static* - line 89

The stored configuration for a company (null = install-wide).

```php
function settings(PDO $conn, ?int $tenantId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Returns:** array{fields:string[], mode:string, notify:string[], record:bool}

**Called from:** [api/tickets/get_mandatory_fields_check.php](/code/files/api/tickets/get_mandatory_fields_check.php.md), [api/tickets/save_mandatory_fields_settings.php](/code/files/api/tickets/save_mandatory_fields_settings.php.md), [tickets/settings/index.php](/code/files/tickets/settings/index.php.md)

### parseAddresses

*public static* - line 112

Split and validate an address list. Invalid entries are dropped, so the saved list and the one actually mailed can never disagree.

```php
function parseAddresses(string $raw): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `string` |  | email addresses as typed, separated by spaces, commas or semicolons |

**Returns:** string[]

**Called from:** [api/tickets/save_mandatory_fields_settings.php](/code/files/api/tickets/save_mandatory_fields_settings.php.md)

### missing

*public static* - line 131

The required fields this ticket has left empty.

```php
function missing(PDO $conn, ?int $tenantId, array $row): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$row` | `array` |  | the ticket as it will be after the save - `tickets` column names, any other keys ignored |

**Returns:** array<string,string> key => English label, in tab order

**Called from:** [api/tickets/get_mandatory_fields_check.php](/code/files/api/tickets/get_mandatory_fields_check.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md)

### fieldAvailable

*public static* - line 153

Can this field be filled in on a ticket belonging to this company?

```php
function fieldAvailable(PDO $conn, ?int $tenantId, string $key): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$key` | `string` |  | a FIELDS key, e.g. 'category' or 'team' |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 164 | `MandatoryFieldsService::fieldAvailable` | read [teams](/code/tables/teams.md) |  |

### assertClosureAllowed

*public static* - line 182

Refuse the close under 'block'. Call BEFORE anything is written: thrown after the update, the ticket would already be closed and the caller would report a failure the database disagrees with.

```php
function assertClosureAllowed(PDO $conn, ?int $tenantId, array $missing): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$missing` | `array` |  | from `missing()` |

**Called from:** [includes/services/tickets.php](/code/files/includes/services/tickets.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md)

### afterClosure

*public static* - line 201

After a close that went ahead with fields empty: write the note and send the email, as configured. Never throws - a failed note or email must not turn a close that happened into an error.

```php
function afterClosure(
    PDO $conn,
    ActorContext $ctx,
    int $ticketId,
    ?int $tenantId,
    array $missing
): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$missing` | `array` |  | from `missing()`, computed before the write |

**Called from:** [includes/services/tickets.php](/code/files/includes/services/tickets.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md)

### writeNote

*public static* - line 239

An internal note from an analyst, or a 'Workflow Note' audit entry when nobody is acting. ticket_notes.analyst_id is NOT NULL with a foreign key to analysts, so a workflow (actor 0) cannot write there - which is why the workflow engine's own "add note" action writes to ticket_audit with a NULL analyst, and this follows it. UTC at rest (GH #126).

```php
function writeNote(PDO $conn, ActorContext $ctx, int $ticketId, string $note): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$note` | `string` |  | the note text |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 243 | `MandatoryFieldsService::writeNote` | **insert** [ticket_notes](/code/tables/ticket_notes.md) (`ticket_id`, `analyst_id`, `note_text`, `is_internal`, `created_datetime`) |  |
| 249 | `MandatoryFieldsService::writeNote` | **insert** [ticket_audit](/code/tables/ticket_audit.md) (`ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |

### closedByLine

*public static* - line 255

"Closed by Jo Bloggs via the API." / "Closed by a workflow."

```php
function closedByLine(ActorContext $ctx): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |

### notify

*private static* - line 272

Emails the chosen people that a ticket was closed with mandatory fields empty, listing them.

```php
function notify(PDO $conn, int $ticketId, array $to, array $missing, string $closedBy): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$to` | `array` |  | the email addresses to tell, from `parseAddresses()` |
| `$missing` | `array` |  | [field key => English label] of the fields left empty |
| `$closedBy` | `string` |  | who closed it, from `closedByLine()` |


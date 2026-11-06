# ChangesService

> Class in [includes/services/changes.php](/code/files/includes/services/changes.php.md), line 41

## Methods

| Method | What it does |
|---|---|
| [createChange()](#createchange) | Create a change in the given (adapter-resolved) company. Returns the new id. The adapter has already resolved + scope-checked `$tenantId`. |
| [updateChange()](#updatechange) | Apply a partial set of updates to a change (a full-form save just sends every key). Writes the UI's exact per-field audit rows, recomputes risk, and fires change.approved on a genuine manual transition into Approved. Returns void; the adapter reloads for its response. |
| [deleteChange()](#deletechange) | Delete a change permanently: attachment files + rows, cascade children. |
| [createComment()](#createcomment) | Append an internal comment to a change. Returns the comment id. |
| [deleteComment()](#deletecomment) | Delete a comment. `$changeId` scopes the delete (API route); pass null to delete by comment id alone (the UI's delete_comment.php has no change id). |
| [saveCab()](#savecab) | Replace the CAB roster (diff-sync add/remove/change-required + audit). |
| [voteCab()](#votecab) | The acting analyst casts a CAB vote; any required Reject sends the change back to Draft, the all/majority threshold on required members auto-approves. Returns [change_id, vote, status_changed, new_status]. |
| [linkTicket()](#linkticket) | Link an incident (ticket) to a change. Returns [change_id, ticket_id, ticket_number, linked]. |
| [unlinkTicket()](#unlinkticket) | Unlink an incident from a change. 404 if not linked. Audited. |
| [canAccessTenantRow()](#canaccesstenantrow) | May the actor access this row of a tenant-scoped table (by its company)? Generic mirror of ProblemsService::canAccessTenantRow. `$table` is a developer literal, never user input. |
| [loadJoined()](#loadjoined) | Load the joined change row (with lookup + people display names), enforcing the actor's company scope; 404 if the change is unknown OR outside scope (indistinguishable, by design). NULL tenant_id normalises to the Default company. No-op at N=1. |
| [auditWrite()](#auditwrite) | Records one action or field change in the change's audit trail. |
| [riskLevel()](#risklevel) | The risk banding (score = likelihood × impact). |
| [resolveLookup()](#resolvelookup) | Resolve a change lookup (type/status/priority/impact) by name or id from input keys "<key>" / "<key>_id". Strict: unknown values are a 422. Returns [id, name, extraCol?] or null when neither key was sent. |
| [lookupDefault()](#lookupdefault) | The default row of a change lookup table: [id, name] or [null, null]. |
| [riskInput()](#riskinput) | Validate a 1-5 risk input. Null/'' clears. |
| [resolveAnalyst()](#resolveanalyst) | Resolve an analyst id to its name; 422 if unknown/inactive (mirrors apiResolveAnalyst). |
| [parseDate()](#parsedate) | Parse a date to UTC 'Y-m-d H:i:s' (mirrors the API's apiParseDate; 400 on garbage). |
| [approvedDispatch()](#approveddispatch) | Fire change.approved (best-effort; the engine swallows its own errors). |

### createChange

*public static* - line 53

Create a change in the given (adapter-resolved) company. Returns the new id. The adapter has already resolved + scope-checked `$tenantId`.

```php
function createChange(PDO $conn, ActorContext $ctx, int $tenantId, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | title (required), lookups as name or <key>_id, category_id, people ids, dates, text bodies, risk, cab_* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 74 | `ChangesService::createChange` | read [change_categories](/code/tables/change_categories.md) |  |
| 115 | `ChangesService::createChange` | **insert** [changes](/code/tables/changes.md) (`tenant_id`, `title`, `change_type_id`, `status_id`, `priority_id`, `impact_id`, `category`, `category_id`, `requester_id`, `assigned_to_id`, `approver_id`, `work_start_datetime`) |  |

**Called from:** [api/change-management/save.php](/code/files/api/change-management/save.php.md), [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md)

### updateChange

*public static* - line 170

Apply a partial set of updates to a change (a full-form save just sends every key). Writes the UI's exact per-field audit rows, recomputes risk, and fires change.approved on a genuine manual transition into Approved. Returns void; the adapter reloads for its response.

```php
function updateChange(PDO $conn, ActorContext $ctx, int $changeId, array $in): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$changeId` | `int` |  | the change to update |
| `$in` | `array` |  | the fields to change, same keys as `createChange()`; absent keys are left alone |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 226 | `ChangesService::updateChange` | read [change_categories](/code/tables/change_categories.md) |  |
| 364 | `ChangesService::updateChange` | **update** [changes](/code/tables/changes.md) |  |

**Called from:** [api/change-management/save.php](/code/files/api/change-management/save.php.md), [api/change-management/update_field.php](/code/files/api/change-management/update_field.php.md), [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md), [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md)

### deleteChange

*public static* - line 383

Delete a change permanently: attachment files + rows, cascade children.

```php
function deleteChange(PDO $conn, ActorContext $ctx, int $changeId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$changeId` | `int` |  | the change to delete, with its attachments |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 387 | `ChangesService::deleteChange` | read [change_attachments](/code/tables/change_attachments.md) |  |
| 395 | `ChangesService::deleteChange` | **delete** [change_attachments](/code/tables/change_attachments.md) |  |
| 396 | `ChangesService::deleteChange` | **delete** [changes](/code/tables/changes.md) |  |

**Called from:** [api/change-management/delete.php](/code/files/api/change-management/delete.php.md), [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md)

### createComment

*public static* - line 414

Append an internal comment to a change. Returns the comment id.

```php
function createComment(PDO $conn, ActorContext $ctx, int $changeId, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$changeId` | `int` |  | the change to comment on |
| `$in` | `array` |  | text (required) - the comment, stored as internal |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 423 | `ChangesService::createComment` | **insert** [change_comments](/code/tables/change_comments.md) (`change_id`, `analyst_id`, `comment_text`, `is_internal`, `created_datetime`) |  |

**Called from:** [api/change-management/save_comment.php](/code/files/api/change-management/save_comment.php.md), [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md)

### deleteComment

*public static* - line 441

Delete a comment. `$changeId` scopes the delete (API route); pass null to delete by comment id alone (the UI's delete_comment.php has no change id).

```php
function deleteComment(PDO $conn, ActorContext $ctx, int $commentId, ?int $changeId = null): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$commentId` | `int` |  | the change_comments row to delete |
| `$changeId` | `?int` | `null` | the change it must belong to, or null to look it up from the comment |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 446 | `ChangesService::deleteComment` | read [change_comments](/code/tables/change_comments.md) |  |
| 455 | `ChangesService::deleteComment` | **delete** [change_comments](/code/tables/change_comments.md) |  |

**Called from:** [api/change-management/delete_comment.php](/code/files/api/change-management/delete_comment.php.md), [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md)

### saveCab

*public static* - line 472

Replace the CAB roster (diff-sync add/remove/change-required + audit).

```php
function saveCab(PDO $conn, ActorContext $ctx, int $changeId, $members): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$changeId` | `int` |  | the change whose CAB roster is replaced |
| `$members` |  |  | the whole roster, [{analyst_id, is_required?}] - is_required defaults to true; must be an array |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 490 | `ChangesService::saveCab` | read [change_cab_members](/code/tables/change_cab_members.md) |  |
| 498 | `ChangesService::saveCab` | read [analysts](/code/tables/analysts.md) |  |
| 506 | `ChangesService::saveCab` | **insert** [change_cab_members](/code/tables/change_cab_members.md) (`change_id`, `analyst_id`, `is_required`, `added_by_id`, `added_datetime`) |  |
| 512 | `ChangesService::saveCab` | **update** [change_cab_members](/code/tables/change_cab_members.md) (`is_required`) |  |
| 520 | `ChangesService::saveCab` | **delete** [change_cab_members](/code/tables/change_cab_members.md) |  |

**Called from:** [api/change-management/save_cab_members.php](/code/files/api/change-management/save_cab_members.php.md), [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md)

### voteCab

*public static* - line 535

The acting analyst casts a CAB vote; any required Reject sends the change back to Draft, the all/majority threshold on required members auto-approves. Returns [change_id, vote, status_changed, new_status].

```php
function voteCab(PDO $conn, ActorContext $ctx, int $changeId, array $in): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$changeId` | `int` |  | the change being voted on |
| `$in` | `array` |  | vote ('Approve', 'Reject' or 'Abstain') and an optional comment |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 546 | `ChangesService::voteCab` | read [change_cab_members](/code/tables/change_cab_members.md) |  |
| 557 | `ChangesService::voteCab` | **update** [change_cab_members](/code/tables/change_cab_members.md) (`vote`, `vote_comment`, `vote_datetime`) |  |
| 561 | `ChangesService::voteCab` | read [analysts](/code/tables/analysts.md) |  |
| 576 | `ChangesService::voteCab` | read [changes](/code/tables/changes.md), read [change_statuses](/code/tables/change_statuses.md) |  |
| 583 | `ChangesService::voteCab` | read [change_statuses](/code/tables/change_statuses.md) |  |
| 590 | `ChangesService::voteCab` | read [change_cab_members](/code/tables/change_cab_members.md) |  |
| 599 | `ChangesService::voteCab` | **update** [changes](/code/tables/changes.md) (`status_id`, `modified_datetime`) |  |
| 607 | `ChangesService::voteCab` | **update** [changes](/code/tables/changes.md) (`status_id`, `approval_datetime`, `modified_datetime`) |  |

**Called from:** [api/change-management/submit_cab_vote.php](/code/files/api/change-management/submit_cab_vote.php.md), [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md)

### linkTicket

*public static* - line 639

Link an incident (ticket) to a change. Returns [change_id, ticket_id, ticket_number, linked].

```php
function linkTicket(PDO $conn, ActorContext $ctx, int $changeId, array $in): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$changeId` | `int` |  | the change to link to |
| `$in` | `array` |  | ticket_id (required) - the incident to link |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 652 | `ChangesService::linkTicket` | read [tickets](/code/tables/tickets.md) |  |
| 669 | `ChangesService::linkTicket` | read [change_tickets](/code/tables/change_tickets.md) |  |
| 675 | `ChangesService::linkTicket` | **insert** [change_tickets](/code/tables/change_tickets.md) (`change_id`, `ticket_id`, `created_by_id`, `created_datetime`) |  |
| 678 | `ChangesService::linkTicket` | **update** [changes](/code/tables/changes.md) (`modified_datetime`) |  |

**Called from:** [api/change-management/link_ticket.php](/code/files/api/change-management/link_ticket.php.md)

### unlinkTicket

*public static* - line 688

Unlink an incident from a change. 404 if not linked. Audited.

```php
function unlinkTicket(PDO $conn, ActorContext $ctx, int $changeId, int $ticketId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$changeId` | `int` |  | the change to unlink from |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 692 | `ChangesService::unlinkTicket` | read [tickets](/code/tables/tickets.md) |  |
| 696 | `ChangesService::unlinkTicket` | **delete** [change_tickets](/code/tables/change_tickets.md) |  |
| 702 | `ChangesService::unlinkTicket` | **update** [changes](/code/tables/changes.md) (`modified_datetime`) |  |

**Called from:** [api/change-management/unlink_ticket.php](/code/files/api/change-management/unlink_ticket.php.md)

### canAccessTenantRow

*private static* - line 717

May the actor access this row of a tenant-scoped table (by its company)? Generic mirror of ProblemsService::canAccessTenantRow. `$table` is a developer literal, never user input.

```php
function canAccessTenantRow(PDO $conn, ActorContext $ctx, string $table, int $rowId): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$table` | `string` |  | a tenant-scoped table name written in code - never user input |
| `$rowId` | `int` |  | the row's id in that table |

### loadJoined

*private static* - line 743

Load the joined change row (with lookup + people display names), enforcing the actor's company scope; 404 if the change is unknown OR outside scope (indistinguishable, by design). NULL tenant_id normalises to the Default company. No-op at N=1.

```php
function loadJoined(PDO $conn, ActorContext $ctx, int $changeId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$changeId` | `int` |  | the change to load |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 746 | `ChangesService::loadJoined` | read [changes](/code/tables/changes.md), read [change_types](/code/tables/change_types.md), read [change_statuses](/code/tables/change_statuses.md), read [change_priorities](/code/tables/change_priorities.md), read [change_impacts](/code/tables/change_impacts.md), read [change_categories](/code/tables/change_categories.md), read [analysts](/code/tables/analysts.md) |  |

### auditWrite

*private static* - line 789

Records one action or field change in the change's audit trail.

```php
function auditWrite(
    PDO $conn,
    int $changeId,
    int $analystId,
    string $actionType,
    ?string $field,
    ?string $old,
    ?string $new
): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$changeId` | `int` |  | the change the entry belongs to |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$actionType` | `string` |  | change_audit.action_type, e.g. 'field_change', 'status_change', 'comment', 'cab_vote' |
| `$field` | `?string` |  | the field label shown in the trail, e.g. 'Status'; null for a comment |
| `$old` | `?string` |  | the value before, as shown |
| `$new` | `?string` |  | the value after, as shown |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 792 | `ChangesService::auditWrite` | **insert** [change_audit](/code/tables/change_audit.md) (`change_id`, `analyst_id`, `action_type`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |

### riskLevel

*private static* - line 802

The risk banding (score = likelihood × impact).

```php
function riskLevel(?int $score): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$score` | `?int` |  | likelihood x impact (1-25), or null when either is unset |

### resolveLookup

*private static* - line 822

Resolve a change lookup (type/status/priority/impact) by name or id from input keys "<key>" / "<key>_id". Strict: unknown values are a 422. Returns [id, name, extraCol?] or null when neither key was sent.

```php
function resolveLookup(
    PDO $conn,
    array $in,
    string $key,
    string $table,
    string $extraCol = ''
): ?array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the request fields |
| `$key` | `string` |  | the input key, e.g. 'status' - '<key>_id' takes an id, '<key>' a name |
| `$table` | `string` |  | the lookup table, written in code - never user input |
| `$extraCol` | `string` | `''` | a further integer column to return as the third element (e.g. 'is_closed'); '' for none |

### lookupDefault

*private static* - line 857

The default row of a change lookup table: [id, name] or [null, null].

This one never had the GH #79 name-lookup fault, but it shared the narrower
half: a default that has since been deactivated is absent from the dropdown,
so selecting it produces a change set to something nobody can choose. Same
ordering as the tickets fix and includes/services/tasks.php — is_active
filters, and falling back through display_order means there is always an
answer as long as one active row exists.

```php
function lookupDefault(PDO $conn, string $table): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$table` | `string` |  | the lookup table, written in code - never user input |

### riskInput

*private static* - line 872

Validate a 1-5 risk input. Null/'' clears.

```php
function riskInput($value, string $field): ?int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$value` |  |  | the 1-5 score from the request; null or '' gives null |
| `$field` | `string` |  | the request field name, used in the error message |

### resolveAnalyst

*private static* - line 889

Resolve an analyst id to its name; 422 if unknown/inactive (mirrors apiResolveAnalyst).

```php
function resolveAnalyst(PDO $conn, int $analystId): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | the analyst to look up (a requester, assignee, approver or CAB member) - not the actor |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 891 | `ChangesService::resolveAnalyst` | read [analysts](/code/tables/analysts.md) |  |

### parseDate

*private static* - line 906

Parse a date to UTC 'Y-m-d H:i:s' (mirrors the API's apiParseDate; 400 on garbage).

```php
function parseDate(string $value, string $field): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$value` | `string` |  | the date or date-time from the request; read as UTC unless it carries an offset |
| `$field` | `string` |  | the request field name, used in the error message |

### approvedDispatch

*private static* - line 925

Fire change.approved (best-effort; the engine swallows its own errors).

```php
function approvedDispatch(int $changeId, ?string $title, ?string $riskLevel, ?int $approverId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$changeId` | `int` |  | the change that was approved |
| `$title` | `?string` |  | the change's title |
| `$riskLevel` | `?string` |  | the change's risk level, e.g. 'High' |
| `$approverId` | `?int` |  | the change's named approver, or null |


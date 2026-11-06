# MorningChecksService

> Class in [includes/services/morning_checks.php](/code/files/includes/services/morning_checks.php.md), line 24

## Methods

| Method | What it does |
|---|---|
| [saveCheck()](#savecheck) | Create (no id) or update (id present) a check. Returns the id. |
| [nullableId()](#nullableid) | "" and 0 and "0" all mean "no assignment"; anything else is an id. |
| [assertExists()](#assertexists) | Reject an id that points at nothing. |
| [listGroups()](#listgroups) | Groups with their routing, plus how many active checks each holds. |
| [saveGroup()](#savegroup) | Adds or updates a morning check group, including the team or analyst it routes to, and returns its id. |
| [deleteGroup()](#deletegroup) | Delete a group. Its checks are NOT deleted — they fall back to ungrouped. |
| [resolveAssignment()](#resolveassignment) | Who is a check routed to? Returns [analystId, label, source] — all null when nobody in particular, which is the default. |
| [deleteCheck()](#deletecheck) | Delete a check + its results, atomically. |
| [loadCheckRow()](#loadcheckrow) | Loads one morning check; throws not found when it does not exist. |
| [recordResult()](#recordresult) | Record (upsert) a result. Returns ['id' => int, 'created' => bool] (created = a new row for that check+date; false = overwrite). |
| [clearResult()](#clearresult) | Put a check back to "not checked" for a date (discussion #64). |
| [recordedDispatch()](#recordeddispatch) | Fire morning_check.recorded (best-effort). |
| [validateDate()](#validatedate) | Strict YYYY-MM-DD — 422 on garbage (the old UI silently substituted today). |
| [saveStatus()](#savestatus) | Create (id null) or update (id present) a status. Returns the id. |
| [deleteStatus()](#deletestatus) | Delete a status; snapshot its label onto affected results (turning them into orphans) first. Returns ['deleted' => int, 'orphaned' => int]. |
| [reorderChecks()](#reorderchecks) | Reorder checks: SortOrder becomes the array index (matches reorder_checks.php). |
| [reorderStatuses()](#reorderstatuses) | Reorder statuses: positions become 10, 20, 30, … (matches reorder_statuses.php), transactional. |
| [normaliseStatuses()](#normalisestatuses) | Map orphan label strings to target StatusIDs. `$mappings` = [{label, statusId}, …]. Returns the total rows updated. Transactional; every target must be active. |

### saveCheck

*public static* - line 35

Create (no id) or update (id present) a check. Returns the id.

```php
function saveCheck(PDO $conn, ActorContext $ctx, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | id (absent = new), name, description, sort_order, is_active, group_id, assigned_analyst_id - null or '' clears |

**Called from:** [api/morning-checks/add_check.php](/code/files/api/morning-checks/add_check.php.md), [api/morning-checks/update_check.php](/code/files/api/morning-checks/update_check.php.md), [api/v1/resources/morning_checks.php](/code/files/api/v1/resources/morning_checks.php.md)

### nullableId

*private static* - line 102

"" and 0 and "0" all mean "no assignment"; anything else is an id.

A `<select>` with a blank option posts "", and casting that to int gives 0,
which would be stored as a foreign key to analyst zero. Every assignment
field goes through here so there is one answer rather than five.

```php
function nullableId($value): ?int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$value` |  |  | an id from the request; null, '', 0 or '0' means none |

### assertExists

*private static* - line 125

Reject an id that points at nothing.

db_verify does not create foreign keys (they live in freeitsm.sql), so an
upgraded install has no database-level guard here. That matters more than
usual for GroupID: the dashboard only shows a grouped check when its group
is active, so a check pointing at a group that does not exist disappears
from the round entirely — silently, and with nothing on screen to explain it.

```php
function assertExists(PDO $conn, ?int $id, string $table, string $idCol, string $field): ?int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `?int` |  | the id to check; null passes straight through |
| `$table` | `string` |  | the table it should be in, written in code - never user input |
| `$idCol` | `string` |  | that table's id column, written in code |
| `$field` | `string` |  | the request field name, used in the error message |

### listGroups

*public static* - line 155

Groups with their routing, plus how many active checks each holds.

```php
function listGroups(PDO $conn, bool $activeOnly = false): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$activeOnly` | `bool` | `false` | true to leave out groups that are switched off |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 157 | `MorningChecksService::listGroups` | read [teams](/code/tables/teams.md), read [analysts](/code/tables/analysts.md) |  |

**Called from:** [api/morning-checks/get_groups.php](/code/files/api/morning-checks/get_groups.php.md)

### saveGroup

*public static* - line 179

Adds or updates a morning check group, including the team or analyst it routes to, and returns its id.

```php
function saveGroup(PDO $conn, ActorContext $ctx, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | id (absent = new), name, description, assigned_team_id, assigned_analyst_id, is_active, sort_order |

**Called from:** [api/morning-checks/save_group.php](/code/files/api/morning-checks/save_group.php.md)

### deleteGroup

*public static* - line 240

Delete a group. Its checks are NOT deleted — they fall back to ungrouped.

⚠️ Deleting a grouping should never delete the work. The FK is
ON DELETE SET NULL and this is stated here as well because "delete group"
is exactly the button somebody presses expecting only the grouping to go.

```php
function deleteGroup(PDO $conn, ActorContext $ctx, int $id): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the morningChecks_Groups row to delete; its checks become ungrouped |

**Called from:** [api/morning-checks/delete_group.php](/code/files/api/morning-checks/delete_group.php.md)

### resolveAssignment

*public static* - line 267

Who is a check routed to? Returns [analystId, label, source] — all null when nobody in particular, which is the default.

Order of precedence, most specific first:
  1. the check's own analyst
  2. its group's analyst
  3. its group's team

A team is a label rather than a single person, so `analystId` stays null
for it — "is this mine?" then means "am I in that team?", which the
dashboard answers from the analyst's own team membership.

```php
function resolveAssignment(array $checkRow): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$checkRow` | `array` |  | a check row joined to its group, with AssignedAnalystID, CheckAnalystName, GroupAnalystID, GroupAnalystName, GroupTeamID and GroupTeamName |

**Called from:** [api/morning-checks/get_todays_checks.php](/code/files/api/morning-checks/get_todays_checks.php.md)

### deleteCheck

*public static* - line 286

Delete a check + its results, atomically.

```php
function deleteCheck(PDO $conn, ActorContext $ctx, int $id): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the check to delete, with its results |

**Called from:** [api/morning-checks/delete_check.php](/code/files/api/morning-checks/delete_check.php.md), [api/v1/resources/morning_checks.php](/code/files/api/v1/resources/morning_checks.php.md)

### loadCheckRow

*private static* - line 306

Loads one morning check; throws not found when it does not exist.

```php
function loadCheckRow(PDO $conn, int $id): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the check to load |

### recordResult

*public static* - line 325

Record (upsert) a result. Returns ['id' => int, 'created' => bool] (created = a new row for that check+date; false = overwrite).

```php
function recordResult(PDO $conn, ActorContext $ctx, array $in): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | check_id (required), status_id or status (a label), notes, date (Y-m-d, default today) |

**Called from:** [api/morning-checks/save_check_result.php](/code/files/api/morning-checks/save_check_result.php.md), [api/v1/resources/morning_checks.php](/code/files/api/v1/resources/morning_checks.php.md)

### clearResult

*public static* - line 414

Put a check back to "not checked" for a date (discussion #64).

⚠️ This DELETES the result row rather than setting its status to null.
"Not checked" is the absence of a result — it is what every check looks
like before anyone touches it — so a row with a null status would be a
second, subtly different way of saying the same thing, and every reader
(the dashboard, the chart, the PDF, the API) would have to learn it.

The consequence is honest and worth stating: clearing a mistake also
discards the note and the attribution that came with it. That is the right
trade for "I clicked green by accident" — the alternative is a half-state
that shows as unchecked but still credits somebody.

Any tickets or tasks raised from that result go too, via ON DELETE CASCADE
on morningChecks_ResultLinks — but the tickets themselves are untouched,
because raising a ticket is not undone by correcting a click.

```php
function clearResult(PDO $conn, ActorContext $ctx, int $checkId, ?string $date = null): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$checkId` | `int` |  | the check to put back to 'not checked' |
| `$date` | `?string` | `null` | the day, Y-m-d; null means today |

**Called from:** [api/morning-checks/clear_check_result.php](/code/files/api/morning-checks/clear_check_result.php.md)

### recordedDispatch

*private static* - line 432

Fire morning_check.recorded (best-effort).

```php
function recordedDispatch(
    int $checkId,
    ?string $checkName,
    int $statusId,
    ?string $statusName,
    string $date
): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$checkId` | `int` |  | the check |
| `$checkName` | `?string` |  | the check's name |
| `$statusId` | `int` |  | the status recorded |
| `$statusName` | `?string` |  | that status's label |
| `$date` | `string` |  | the day the result is for, Y-m-d |

### validateDate

*private static* - line 446

Strict YYYY-MM-DD — 422 on garbage (the old UI silently substituted today).

```php
function validateDate(string $value, string $field): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$value` | `string` |  | the date from the request, Y-m-d |
| `$field` | `string` |  | the request field name, used in the error message |

### saveStatus

*public static* - line 465

Create (id null) or update (id present) a status. Returns the id.

```php
function saveStatus(PDO $conn, ActorContext $ctx, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | id (null or absent = new), label (up to 50), colour (#rrggbb), requires_notes, is_active, sort_order |

**Called from:** [api/morning-checks/save_status.php](/code/files/api/morning-checks/save_status.php.md)

### deleteStatus

*public static* - line 513

Delete a status; snapshot its label onto affected results (turning them into orphans) first. Returns ['deleted' => int, 'orphaned' => int].

```php
function deleteStatus(PDO $conn, ActorContext $ctx, int $id): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the morningChecks_Statuses row to delete |

**Called from:** [api/morning-checks/delete_status.php](/code/files/api/morning-checks/delete_status.php.md)

### reorderChecks

*public static* - line 552

Reorder checks: SortOrder becomes the array index (matches reorder_checks.php).

```php
function reorderChecks(PDO $conn, ActorContext $ctx, array $order): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$order` | `array` |  | every check id, top first; each check's SortOrder becomes its position |

**Called from:** [api/morning-checks/reorder_checks.php](/code/files/api/morning-checks/reorder_checks.php.md)

### reorderStatuses

*public static* - line 565

Reorder statuses: positions become 10, 20, 30, … (matches reorder_statuses.php), transactional.

```php
function reorderStatuses(PDO $conn, ActorContext $ctx, array $order): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$order` | `array` |  | every status id, top first; SortOrder becomes 10, 20, 30... |

**Called from:** [api/morning-checks/reorder_statuses.php](/code/files/api/morning-checks/reorder_statuses.php.md)

### normaliseStatuses

*public static* - line 586

Map orphan label strings to target StatusIDs. `$mappings` = [{label, statusId}, …]. Returns the total rows updated. Transactional; every target must be active.

```php
function normaliseStatuses(PDO $conn, ActorContext $ctx, array $mappings): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$mappings` | `array` |  | [{label, statusId}] - each old label and the active status its results should now use |

**Called from:** [api/morning-checks/normalise_status.php](/code/files/api/morning-checks/normalise_status.php.md)


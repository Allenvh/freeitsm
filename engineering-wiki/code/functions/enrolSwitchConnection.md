# enrolSwitchConnection()

Move an analyst onto another connection. Returns true when anything moved.

🔴 THE OLD CONNECTION TAKES ITS EVENTS BACK FIRST. Each event is removed
through the connection that wrote it (calendarSyncRemoveRow), so this has to
happen before the enrolment forgets it. Everything tied to the old
connection goes with it: the sign-in, the change token, a Microsoft
subscription, and the calendar address whenever either side is CalDAV (a
CalDAV address is a web address that means nothing to Microsoft, and the
other way round). A push that was on is switched off here; the caller
switches it back on once the new connection has been checked.

```php
function enrolSwitchConnection(
    PDO $conn,
    int $analystId,
    array $enrolment,
    ?array $current,
    array $target
): bool
```

Defined in [api/tickets/calendar_enrolment.php](/code/files/api/tickets/calendar_enrolment.php.md), line 129.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$enrolment` | `array` |  | the analyst's calendar_enrolments row, as from `calendarSyncEnrolment()` |
| `$current` | `?array` |  | the calendar_connections row the analyst is on now; null when none |
| `$target` | `array` |  | the calendar_connections row to move them onto |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 135 | `enrolSwitchConnection` | **update** [calendar_enrolments](/code/tables/calendar_enrolments.md) (`connection_id`) |  |
| 155 | `enrolSwitchConnection` | **update** [calendar_enrolments](/code/tables/calendar_enrolments.md) (`connection_id`, `mode`, `calendar_address`, `credentials`, `delta_token`, `delta_synced_datetime`, `subscription_id`, `subscription_expires`, `subscription_secret`, `last_error`, `updated_datetime`) |  |

## Calls

[calendarSyncProviderFor()](/code/functions/calendarSyncProviderFor.md), [calendarSyncRemoveAllForAnalyst()](/code/functions/calendarSyncRemoveAllForAnalyst.md)

## Called from

- [api/tickets/calendar_enrolment.php](/code/files/api/tickets/calendar_enrolment.php.md)


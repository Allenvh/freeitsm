# calendarSyncRemoveAllForAnalyst()

Everything FreeITSM put in one analyst's calendar, removed.

```php
function calendarSyncRemoveAllForAnalyst(PDO $conn, int $analystId): void
```

Defined in [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md), line 416.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 420 | `calendarSyncRemoveAllForAnalyst` | read [calendar_sync_events](/code/tables/calendar_sync_events.md) |  |

## Calls

[calendarSyncRemoveRow()](/code/functions/calendarSyncRemoveRow.md), [calendarSyncSchemaReady()](/code/functions/calendarSyncSchemaReady.md)

## Called from

- [api/tickets/calendar_enrolment.php](/code/files/api/tickets/calendar_enrolment.php.md) - in [enrolSwitchConnection()](/code/functions/enrolSwitchConnection.md)


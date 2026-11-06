# calendarSyncEventFromTicket()

The canonical event shape (see CalendarSyncProvider) for one ticket row.

```php
function calendarSyncEventFromTicket(array $t): array
```

Defined in [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md), line 435.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$t` | `array` |  | the ticket row as `calendarSyncReconcileTicket()` loads it - work_*, subject, sensitivity, requester, status and priority names |

**Returns:** `array`

## Calls

[requestScheme()](/code/functions/requestScheme.md), [ticketSensitivityNormalise()](/code/functions/ticketSensitivityNormalise.md)

## Called from

- [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md) - in [calendarSyncReconcileTicket()](/code/functions/calendarSyncReconcileTicket.md)


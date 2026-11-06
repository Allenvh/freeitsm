# calendarSyncReconcileTicket()

Make the calendar match the ticket.

```php
function calendarSyncReconcileTicket(PDO $conn, int $ticketId, bool $gone = false): void
```

Defined in [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md), line 34.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$gone` | `bool` | `false` | true when the ticket is going away entirely (trashed or purged) and its row may no longer be readable. |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 39 | `calendarSyncReconcileTicket` | read [calendar_sync_events](/code/tables/calendar_sync_events.md) |  |
| 51 | `calendarSyncReconcileTicket` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md), read [users](/code/tables/users.md) |  |
| 119 | `calendarSyncReconcileTicket` | **update** [calendar_sync_events](/code/tables/calendar_sync_events.md) (`updated_datetime`) |  |
| 127 | `calendarSyncReconcileTicket` | **update** [calendar_sync_events](/code/tables/calendar_sync_events.md) (`remote_event_id`, `remote_calendar`, `updated_datetime`) |  |
| 138 | `calendarSyncReconcileTicket` | **insert** [calendar_sync_events](/code/tables/calendar_sync_events.md) (`ticket_id`, `analyst_id`, `connection_id`, `remote_event_id`, `remote_calendar`) |  |

## Calls

[calendarSyncClearError()](/code/functions/calendarSyncClearError.md), [calendarSyncConnectionFor()](/code/functions/calendarSyncConnectionFor.md), [calendarSyncEnrolment()](/code/functions/calendarSyncEnrolment.md), [calendarSyncEventFromTicket()](/code/functions/calendarSyncEventFromTicket.md), [calendarSyncProviderFor()](/code/functions/calendarSyncProviderFor.md), [calendarSyncRecordError()](/code/functions/calendarSyncRecordError.md), [calendarSyncRemoveRow()](/code/functions/calendarSyncRemoveRow.md), [calendarSyncSchemaReady()](/code/functions/calendarSyncSchemaReady.md), [ticketSensitivitySelectSql()](/code/functions/ticketSensitivitySelectSql.md)

## Called from

- [api/tickets/calendar_enrolment.php](/code/files/api/tickets/calendar_enrolment.php.md) - in [enrolBackfillTickets()](/code/functions/enrolBackfillTickets.md)
- [includes/services/tickets.php](/code/files/includes/services/tickets.php.md) - in `TicketsService::updateTicket`, `TicketsService::deleteTicket`, `TicketsService::restoreTicket`
- [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md) - in [ticketSensitivityRaise()](/code/functions/ticketSensitivityRaise.md)


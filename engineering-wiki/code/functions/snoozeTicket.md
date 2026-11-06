# snoozeTicket()

Put one ticket to sleep. Caller has already checked access. Deliberately does NOT bump updated_datetime — snoozing is the desk deciding not to act, not activity on the ticket, and shoving it to the top of a sort by last-updated on its way out of the list would be a lie.

```php
function snoozeTicket(
    PDO $conn,
    int $ticketId,
    int $analystId,
    string $untilUtc,
    string $reason
): void
```

Defined in [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md), line 223.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$untilUtc` | `string` |  | the wake time, UTC 'Y-m-d H:i:s' (from resolveSnoozeUntil) |
| `$reason` | `string` |  | the analyst's note on why; '' stores NULL; cut to 255 characters |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 228 | `snoozeTicket` | **update** [tickets](/code/tables/tickets.md) (`snoozed_until`, `snoozed_at`, `snoozed_by`, `snooze_reason`) |  |

## Calls

[snoozeAudit()](/code/functions/snoozeAudit.md), [snoozeSchemaReady()](/code/functions/snoozeSchemaReady.md)

## Called from

- [api/tickets/snooze_ticket.php](/code/files/api/tickets/snooze_ticket.php.md)


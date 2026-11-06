# ticketViewsReady()

> [!TIP]
> **Developer guide on the GitHub wiki:** [Ticket Views Developer Guide](https://github.com/edmozley/freeitsm/wiki/Ticket-Views-Developer-Guide)

Who has looked at a ticket (discussion #62, step 2).

WHY
---
Managers are coming to the self-service portal. If a manager can read the
tickets of the people they manage, those people are owed the knowledge of
WHEN they did. So the requester sees, on their own ticket, every time a
manager opened it - shown by default, not behind a button.

WHAT THE REQUESTER DOES NOT SEE: service-desk views. Ed's reasoning: "Viewed
by the service desk, Monday 09:02" invites "why did you look at my ticket and
not do anything about it", which is pressure the desk should not be under for
doing its job. Analysts' views are recorded all the same, and appear in the
ticket's audit trail for analysts.

STORAGE
-------
`ticket_views`, one row per viewer per UTC day, kept by the unique key
uq_ticket_views_day - opening a ticket twenty times is one row with
view_count 20. Its own table rather than ticket_audit, because the audit
trail's actor can only be an analyst and a manager reading in the portal is
not one; and because views are many times more frequent than changes.

⚠️ The unique key is what makes the upsert below an upsert. Database
Verification creates tables without constraints, so it is restored from
db_verify_indexes.php - without it every view would be a new row.

```php
function ticketViewsReady(PDO $conn): bool
```

Defined in [includes/ticket_views.php](/code/files/includes/ticket_views.php.md), line 33.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 | `ticketViewsReady` | read [ticket_views](/code/tables/ticket_views.md) |  |

## Called from

- [api/self-service/get_ticket_detail.php](/code/files/api/self-service/get_ticket_detail.php.md)
- [api/system/managers.php](/code/files/api/system/managers.php.md) - in [managersStatus()](/code/functions/managersStatus.md)
- [includes/managers.php](/code/files/includes/managers.php.md) - in [managersActive()](/code/functions/managersActive.md)
- [includes/ticket_views.php](/code/files/includes/ticket_views.php.md) - in [ticketViewRecord()](/code/functions/ticketViewRecord.md), [ticketViewsAsAuditRows()](/code/functions/ticketViewsAsAuditRows.md), [ticketViewsForRequester()](/code/functions/ticketViewsForRequester.md)


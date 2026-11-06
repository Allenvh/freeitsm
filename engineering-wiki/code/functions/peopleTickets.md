# peopleTickets()

Tickets raised by the person / in the company: the latest, and how many are open.

```php
function peopleTickets(PDO $conn, int $analystId, array $who): array
```

Defined in [includes/people.php](/code/files/includes/people.php.md), line 326.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$who` | `array` |  | ['user' => users id] for one person, or ['company' => tenant id] for a company |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 334 | `peopleTickets` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 343 | `peopleTickets` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md), read [analysts](/code/tables/analysts.md), read [users](/code/tables/users.md) |  |

## Calls

[entityLink()](/code/functions/entityLink.md), [peopleCompanyMatch()](/code/functions/peopleCompanyMatch.md), [peopleScope()](/code/functions/peopleScope.md)

## Called from

- [includes/people.php](/code/files/includes/people.php.md) - in [personDetail()](/code/functions/personDetail.md), [companyDetail()](/code/functions/companyDetail.md)


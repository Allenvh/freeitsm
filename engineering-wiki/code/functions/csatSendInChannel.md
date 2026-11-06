# csatSendInChannel()

Ask a chat customer for a 1-5 rating in their chat. Returns the response id, or throws - having removed its own row - so the caller can fall back to email.

```php
function csatSendInChannel(PDO $conn, int $ticketId, array $target, ?int $analystId): int
```

Defined in [includes/csat.php](/code/files/includes/csat.php.md), line 184.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$target` | `array` |  | where to ask, as `csatTicketChannel()` returns it: channel row, from, address |
| `$analystId` | `?int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 190 | `csatSendInChannel` | **insert** [ticket_csat_responses](/code/tables/ticket_csat_responses.md) (`ticket_id`, `token`, `sent_datetime`, `analyst_id`, `created_at`, `channel_id`, `channel_from`) |  |
| 200 | `csatSendInChannel` | **delete** [ticket_csat_responses](/code/tables/ticket_csat_responses.md) |  |

## Calls

[csatChannelLocale()](/code/functions/csatChannelLocale.md), [csatGenerateToken()](/code/functions/csatGenerateToken.md), [messagingProvider()](/code/functions/messagingProvider.md)

## Called from

- [includes/csat.php](/code/files/includes/csat.php.md) - in [sendCsatSurvey()](/code/functions/sendCsatSurvey.md)


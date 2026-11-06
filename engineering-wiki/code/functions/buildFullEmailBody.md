# buildFullEmailBody()

Build full email body with thread for sending to recipient Analyst's content + reply marker + quoted thread from DB

```php
function buildFullEmailBody($conn, $ticketId, $analystBody, $type)
```

Defined in [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md), line 697.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` |  |  | the ticket whose emails make up the quoted thread |
| `$analystBody` |  |  | the analyst's new HTML text, which goes above the marker |
| `$type` |  |  | 'reply' or 'forward' - a forward gets a "Forwarded message" heading |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 699 | `buildFullEmailBody` | read [tickets](/code/tables/tickets.md) |  |
| 705 | `buildFullEmailBody` | read [emails](/code/tables/emails.md) |  |

## Calls

[fmt_local()](/code/functions/fmt_local.md), [stripQuotedContent()](/code/functions/stripQuotedContent.md)

## Called from

- [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md)


# renderReplyTemplate()

Resolve a template body against a ticket, escaping every substituted value.

Returns the body unchanged when the ticket can't be read — a template full of
literal [requester_first_name] is obviously wrong to the analyst about to send it,
whereas silently blanking the codes looks like a finished sentence and gets sent.

```php
function renderReplyTemplate(PDO $conn, string $body, int $ticketId): string
```

Defined in [includes/reply_templates.php](/code/files/includes/reply_templates.php.md), line 72.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$body` | `string` |  | the template text with [merge_code] placeholders (trusted HTML; the values put in are escaped) |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Calls

[buildTicketMergeData()](/code/functions/buildTicketMergeData.md)

## Called from

- [api/tickets/render_reply_template.php](/code/files/api/tickets/render_reply_template.php.md)


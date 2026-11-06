# oversizeMeasure()

Fetch (or take the pre-loaded) attachments of one email and measure them. Returns [the attachment list to hand on, [['name','size'], ...] for the file attachments in order]. Sizes are the DECODED bytes where the content is here, else the size the provider reports.

```php
function oversizeMeasure($conn, array $email, $accessToken): array
```

Defined in [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), line 1457.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$email` | `array` |  | one message in Graph's shape; attachments_inline is used when the provider supplied them |
| `$accessToken` |  |  | the Graph access token, used to fetch attachments when they are not inline |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1461 | `oversizeMeasure` | read [emails](/code/tables/emails.md) |  |

## Calls

[fetchEmailAttachments()](/code/functions/fetchEmailAttachments.md)

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md)


# attachmentIngestNotice()

The block appended to a message body when attachments were not accepted, so the outcome is visible on the ticket instead of a file quietly vanishing. Returns '' when there is nothing to say.

```php
function attachmentIngestNotice(array $rejected, array $quarantined): string
```

Defined in [includes/uploads.php](/code/files/includes/uploads.php.md), line 986.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rejected` | `array` |  | files not saved, each ['name' => original filename, 'reason' => why] |
| `$quarantined` | `array` |  | files stored as inert .bin, each ['name' => original filename, 'reason' => why] |

**Returns:** `string`

## Called from

- [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md)
- [api/self-service/reply_ticket.php](/code/files/api/self-service/reply_ticket.php.md)
- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [saveEmailToDatabase()](/code/functions/saveEmailToDatabase.md)
- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [ingestInboundMessage()](/code/functions/ingestInboundMessage.md)


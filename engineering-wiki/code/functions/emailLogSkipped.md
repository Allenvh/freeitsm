# emailLogSkipped()

Convenience: an email that was deliberately NOT sent, and why.

⚠️ THIS IS THE ONE THAT MATTERS TWELVE MONTHS LATER. When email templates are
scoped to particular senders, a sender matching none of them gets no reply —
which is correct, and is also indistinguishable from a fault unless somebody
wrote it down. The scenario this exists for is a new customer domain being
onboarded long after the templates were set up, by people who never saw the
settings screen: without this row, the only evidence is an absence, and the
only way to understand it is to already suspect the cause.

The mailbox may legitimately be null: not sending happens before a mailbox is
looked up, and the row is still worth having.

```php
function emailLogSkipped(
    ?PDO $conn,
    ?array $mailbox,
    string $route,
    string $to,
    string $subject,
    string $reason,
    ?int $ticketId = null
): void
```

Defined in [includes/email_log.php](/code/files/includes/email_log.php.md), line 172.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `?PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$mailbox` | `?array` |  | the target_mailboxes row it would have gone through, or null if none was looked up |
| `$route` | `string` |  | a key of EMAIL_LOG_ROUTES saying which path decided not to send |
| `$to` | `string` |  | the address that would have received it |
| `$subject` | `string` |  | the subject the email would have had |
| `$reason` | `string` |  | why it was not sent, in plain words - stored as the row's message |
| `$ticketId` | `?int` | `null` | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Calls

[emailLogRecord()](/code/functions/emailLogRecord.md)

## Called from

- [includes/template_email.php](/code/files/includes/template_email.php.md) - in [sendTemplateEmail()](/code/functions/sendTemplateEmail.md)


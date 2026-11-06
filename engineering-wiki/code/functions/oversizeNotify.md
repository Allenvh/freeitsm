# oversizeNotify()

Tell people about an oversized email: the alert to the configured addresses, and the reply to the sender. Returns processing-log steps for the inbound log.

⚠️ THE AUTO-REPLY MUST NOT BE ABLE TO LOOP. Two systems that each answer an
oversized email with an email can bounce forever, so the reply is never sent to
an automated sender (no-reply, mailer-daemon, postmaster, bounce...), never to
the mailbox itself, and at most once an hour to the same sender from the same
mailbox - checked against the inbound log, which already records every reply.

```php
function oversizeNotify(
    PDO $conn,
    array $mailbox,
    array $rules,
    string $fromAddress,
    string $fromName,
    string $subject,
    array $atts,
    array $plan,
    string $outcome,
    ?int $ticketId = null
): array
```

Defined in [includes/attachment_limits.php](/code/files/includes/attachment_limits.php.md), line 260.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$mailbox` | `array` |  | the target_mailboxes row the email arrived at - id, name and target_mailbox are read |
| `$rules` | `array` |  | the mailbox's rules from `oversizeRules()` |
| `$fromAddress` | `string` |  | the sender's address, from the email (untrusted) - the auto-reply goes here |
| `$fromName` | `string` |  | the sender's display name, shown in the alert |
| `$subject` | `string` |  | the email's subject, shown in the alert and the auto-reply |
| `$atts` | `array` |  | [['name','size'], ...]   `$plan` from `oversizePlan()` |
| `$plan` | `array` |  | the decision from `oversizePlan()` for these attachments - its 'reasons' are listed |
| `$outcome` | `string` |  | 'rejected' (no ticket) \| 'stripped' (ticket kept, files removed) |
| `$ticketId` | `?int` | `null` | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 273 | `oversizeNotify` | read [tickets](/code/tables/tickets.md) |  |

## Calls

[mailboxSendSimple()](/code/functions/mailboxSendSimple.md), [oversizeMbWords()](/code/functions/oversizeMbWords.md), [oversizeReplySkipReason()](/code/functions/oversizeReplySkipReason.md)

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md)


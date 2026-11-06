# oversizeReplySkipReason()

Why NOT to auto-reply to this sender, or null to go ahead.

```php
function oversizeReplySkipReason(
    PDO $conn,
    int $mailboxId,
    string $from,
    string $mailboxAddress
): ?string
```

Defined in [includes/attachment_limits.php](/code/files/includes/attachment_limits.php.md), line 325.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$mailboxId` | `int` |  | the target_mailboxes id the email arrived at |
| `$from` | `string` |  | the sender's address, from the email (untrusted) |
| `$mailboxAddress` | `string` |  | the mailbox's own address, so it never replies to itself |

**Returns:** `?string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 335 | `oversizeReplySkipReason` | read [mailbox_activity_log](/code/tables/mailbox_activity_log.md) |  |

## Called from

- [includes/attachment_limits.php](/code/files/includes/attachment_limits.php.md) - in [oversizeNotify()](/code/functions/oversizeNotify.md)


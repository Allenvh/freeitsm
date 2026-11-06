# failMailboxCheck()

Report a check that could not run at all: record it, say so, stop.

One function rather than a recording line beside each of the eight exits,
because the exit somebody forgets to update is exactly the silent one.

```php
function failMailboxCheck($conn, $mailboxId, string $error, array $extra = [])
```

Defined in [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), line 569.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$mailboxId` |  |  | the target_mailboxes id the check was for |
| `$error` | `string` |  | the reason, recorded on the mailbox and sent back as the error |
| `$extra` | `array` | `[]` | more fields to add to the JSON reply |

## Calls

[recordMailboxCheckOutcome()](/code/functions/recordMailboxCheckOutcome.md)

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md)


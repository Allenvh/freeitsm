# recordMailboxCheckOutcome()

Record how a mail check went — stamping the time AND what went wrong.

🔴 WHY THIS EXISTS. A mailbox stopped collecting for eighteen hours and said
nothing. The Azure client secret had expired; the provider said so plainly;
FreeITSM discarded the sentence and left the mailbox looking green and
"Authenticated" in the list. The only way to find out was to click Check and
read the response.

So an outcome is now written down. A clean check clears the error; a failed
one keeps the provider's own words, and mailbox health turns that into a
non-dismissible error on the mailbox row.

⚠️ PARTIAL FAILURE COUNTS AS FAILURE. The same outage also produced checks
that returned success while saving nothing — "1 email found, 0 saved" with
the reason buried in an errors array nobody was reading. A check that fetched
mail it could not turn into tickets has not worked, whatever its status said.

```php
function recordMailboxCheckOutcome($conn, $mailboxId, ?string $error = null)
```

Defined in [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), line 541.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$mailboxId` |  |  | the target_mailboxes id the check was for |
| `$error` | `?string` | `null` | null on a clean run; the reason otherwise. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 544 | `recordMailboxCheckOutcome` | **update** [target_mailboxes](/code/tables/target_mailboxes.md) (`last_checked_datetime`, `last_error`, `last_error_datetime`) |  |

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [failMailboxCheck()](/code/functions/failMailboxCheck.md), [updateLastChecked()](/code/functions/updateLastChecked.md)


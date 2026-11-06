# emailLogRecord()

Record one send attempt. NEVER throws and never returns a failure: logging a send must not be able to break the send it is logging, and must not turn a delivered email into an error the caller reports as undelivered.

`$mailbox` may be null — a send can fail before any mailbox is resolved, and that is
one of the more useful things to have recorded.

```php
function emailLogRecord(
    ?PDO $conn,
    ?array $mailbox,
    string $route,
    string $to,
    string $subject,
    $sent,
    ?string $error = null,
    ?int $ticketId = null
): void
```

Defined in [includes/email_log.php](/code/files/includes/email_log.php.md), line 77.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `?PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$mailbox` | `?array` |  | the target_mailboxes row used (id, provider, auth_mode read), or null if none |
| `$route` | `string` |  | a key of EMAIL_LOG_ROUTES saying which path sent it (cut to 30 characters) |
| `$to` | `string` |  | the recipient address(es) as sent (cut to 255 characters) |
| `$subject` | `string` |  | the email's subject line (cut to 500 characters) |
| `$sent` |  |  | bool for sent/failed, or a status string |
| `$error` | `?string` | `null` | the provider's error or the skip reason, null for a successful send (cut to 2000) |
| `$ticketId` | `?int` | `null` | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 91 | `emailLogRecord` | **insert** [email_send_log](/code/tables/email_send_log.md) (`mailbox_id`, `ticket_id`, `route`, `provider`, `auth_mode`, `to_address`, `subject`, `status`, `error_message`, `created_datetime`) |  |

## Called from

- [api/change-management/send_share_email.php](/code/files/api/change-management/send_share_email.php.md)
- [api/knowledge/send_share_email.php](/code/files/api/knowledge/send_share_email.php.md)
- [includes/email_log.php](/code/files/includes/email_log.php.md) - in [emailLogSent()](/code/functions/emailLogSent.md), [emailLogFailed()](/code/functions/emailLogFailed.md), [emailLogSkipped()](/code/functions/emailLogSkipped.md)


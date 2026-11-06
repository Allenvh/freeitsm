# saveSentEmail()

Save sent email to database, including whatever was attached to it.

⚠️ THE ATTACHMENTS HAVE ALREADY GONE. This runs after the send, so nothing
here can refuse a file — it is recording what left the building, not deciding
whether it may. That is why a file the upload policy dislikes is still noted
on the message rather than silently dropped: the ticket has to show what the
requester actually received.

It still stores through includes/uploads.php like every other path, because
these land in tickets/attachments/ inside the web root and a name chosen by
somebody else must never become a file the server would execute.

```php
function saveSentEmail($conn, $ticketId, $mailbox, $to, $cc, $subject, $body, $attachments = [])
```

Defined in [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md), line 818.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` |  |  | the ticket the email was sent from |
| `$mailbox` |  |  | the target_mailboxes row it was sent from - id, target_mailbox and mailbox_name are stored |
| `$to` |  |  | the To addresses as typed |
| `$cc` |  |  | the Cc addresses as typed |
| `$subject` |  |  | the email subject |
| `$body` |  |  | the analyst's HTML text, without the quoted thread |
| `$attachments` |  | `[]` | the files the analyst attached, from the request: [{name, type, content (base64)}, ...] |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 820 | `saveSentEmail` | **insert** [emails](/code/tables/emails.md) (`subject`, `from_address`, `from_name`, `to_recipients`, `cc_recipients`, `received_datetime`, `body_content`, `body_type`, `ticket_id`, `is_initial`, `direction`, `mailbox_id`) |  |
| 844 | `saveSentEmail` | **update** [tickets](/code/tables/tickets.md) (`updated_datetime`) |  |

## Calls

[saveSentAttachments()](/code/functions/saveSentAttachments.md)

## Called from

- [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md)


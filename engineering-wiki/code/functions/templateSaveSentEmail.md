# templateSaveSentEmail()

Save the sent template email to the emails table.

```php
function templateSaveSentEmail(
    PDO $conn,
    int $ticketId,
    array $mailbox,
    string $to,
    string $subject,
    string $body
): void
```

Defined in [includes/template_email.php](/code/files/includes/template_email.php.md), line 748.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$mailbox` | `array` |  | the target_mailboxes row it was sent from (id, target_mailbox, name) |
| `$to` | `string` |  | the recipient's address |
| `$subject` | `string` |  | the subject as sent |
| `$body` | `string` |  | the HTML body as sent |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 750 | `templateSaveSentEmail` | **insert** [emails](/code/tables/emails.md) (`subject`, `from_address`, `from_name`, `to_recipients`, `received_datetime`, `body_content`, `body_type`, `ticket_id`, `is_initial`, `direction`, `mailbox_id`) |  |
| 767 | `templateSaveSentEmail` | **update** [tickets](/code/tables/tickets.md) (`updated_datetime`) |  |

## Called from

- [includes/template_email.php](/code/files/includes/template_email.php.md) - in [sendTemplateEmail()](/code/functions/sendTemplateEmail.md)
- [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) - in `WorkflowEngine::action_send_email`


# gmailGetValidAccessToken()

Get a valid Google access token, refreshing if expired.

```php
function gmailGetValidAccessToken(PDO $conn, array $mailbox, array $tokenData): string
```

Defined in [includes/gmail.php](/code/files/includes/gmail.php.md), line 81.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$mailbox` | `array` |  | the target_mailboxes row, decrypted - passed on to `gmailRefreshAccessToken()` |
| `$tokenData` | `array` |  | the decoded token_data; refreshed when expires_at (Unix seconds) is under 5 minutes away |

**Returns:** `string`

## Calls

[gmailRefreshAccessToken()](/code/functions/gmailRefreshAccessToken.md)

## Called from

- [api/auth/request_password_reset.php](/code/files/api/auth/request_password_reset.php.md)
- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md)
- [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md)
- [api/tickets/verify_mailbox_folder.php](/code/files/api/tickets/verify_mailbox_folder.php.md)
- [includes/mailbox_send.php](/code/files/includes/mailbox_send.php.md) - in [mailboxSendSimple()](/code/functions/mailboxSendSimple.md)
- [includes/self_service_email.php](/code/files/includes/self_service_email.php.md) - in [ssSendSystemEmail()](/code/functions/ssSendSystemEmail.md)
- [includes/template_email.php](/code/files/includes/template_email.php.md) - in [sendTemplateEmail()](/code/functions/sendTemplateEmail.md), [internalTicketEmail()](/code/functions/internalTicketEmail.md)
- [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) - in `WorkflowEngine::action_send_email`


# getValidAccessToken()

> [!NOTE]
> Defined in 4 places (each behind a function_exists() check, or in files never loaded together): [api/change-management/send_share_email.php](/code/files/api/change-management/send_share_email.php.md), [api/knowledge/send_share_email.php](/code/files/api/knowledge/send_share_email.php.md), [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md)

Get valid access token (refresh if expired)

```php
function getValidAccessToken($conn, $mailbox, $tokenData)
```

Defined in [api/change-management/send_share_email.php](/code/files/api/change-management/send_share_email.php.md), line 276.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$mailbox` |  |  | the target_mailboxes row, decrypted (id and the azure_* columns are used for a refresh) |
| `$tokenData` |  |  | the mailbox's decoded token_data: access_token, refresh_token, expires_at (Unix time) |

## Calls

[refreshAccessToken()](/code/functions/refreshAccessToken.md), [saveTokenData()](/code/functions/saveTokenData.md)

## Called from

- [api/change-management/send_share_email.php](/code/files/api/change-management/send_share_email.php.md) - in [sendMailboxEmail()](/code/functions/sendMailboxEmail.md)
- [api/knowledge/send_share_email.php](/code/files/api/knowledge/send_share_email.php.md) - in [sendMailboxEmail()](/code/functions/sendMailboxEmail.md)
- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md)
- [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md)


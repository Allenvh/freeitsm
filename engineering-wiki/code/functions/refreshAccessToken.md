# refreshAccessToken()

> [!NOTE]
> Defined in 4 places (each behind a function_exists() check, or in files never loaded together): [api/change-management/send_share_email.php](/code/files/api/change-management/send_share_email.php.md), [api/knowledge/send_share_email.php](/code/files/api/knowledge/send_share_email.php.md), [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md)

Refresh the access token using mailbox configuration

```php
function refreshAccessToken($mailbox, $refreshToken)
```

Defined in [api/change-management/send_share_email.php](/code/files/api/change-management/send_share_email.php.md), line 305.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$mailbox` |  |  | the target_mailboxes row, decrypted - azure_tenant_id, azure_client_id/secret, oauth_scopes |
| `$refreshToken` |  |  | the OAuth refresh token to trade for a new access token |

## Calls

[sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [api/change-management/send_share_email.php](/code/files/api/change-management/send_share_email.php.md) - in [getValidAccessToken()](/code/functions/getValidAccessToken.md)
- [api/knowledge/send_share_email.php](/code/files/api/knowledge/send_share_email.php.md) - in [getValidAccessToken()](/code/functions/getValidAccessToken.md)
- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [getValidAccessToken()](/code/functions/getValidAccessToken.md)
- [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md) - in [getValidAccessToken()](/code/functions/getValidAccessToken.md)


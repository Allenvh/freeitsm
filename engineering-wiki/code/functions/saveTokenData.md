# saveTokenData()

> [!NOTE]
> Defined in 4 places (each behind a function_exists() check, or in files never loaded together): [api/change-management/send_share_email.php](/code/files/api/change-management/send_share_email.php.md), [api/knowledge/send_share_email.php](/code/files/api/knowledge/send_share_email.php.md), [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md)

Save token data to database

```php
function saveTokenData($conn, $mailboxId, $tokenData)
```

Defined in [api/change-management/send_share_email.php](/code/files/api/change-management/send_share_email.php.md), line 353.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$mailboxId` |  |  | the target_mailboxes id to update |
| `$tokenData` |  |  | the token array to store (encrypted) in token_data, as built by `refreshAccessToken()` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 357 | `saveTokenData` | **update** [target_mailboxes](/code/tables/target_mailboxes.md) (`token_data`) |  |

## Calls

[encryptValue()](/code/functions/encryptValue.md)

## Called from

- [api/change-management/send_share_email.php](/code/files/api/change-management/send_share_email.php.md) - in [getValidAccessToken()](/code/functions/getValidAccessToken.md)
- [api/knowledge/send_share_email.php](/code/files/api/knowledge/send_share_email.php.md) - in [getValidAccessToken()](/code/functions/getValidAccessToken.md)
- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [getValidAccessToken()](/code/functions/getValidAccessToken.md)
- [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md) - in [getValidAccessToken()](/code/functions/getValidAccessToken.md)


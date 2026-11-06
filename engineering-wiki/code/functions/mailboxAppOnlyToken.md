# mailboxAppOnlyToken()

App-only access token via the client-credentials flow (no user, no interactive sign-in). Cached in target_mailboxes.token_data until it expires (app-only tokens carry no refresh token — we just re-fetch).

```php
function mailboxAppOnlyToken($conn, $mailbox)
```

Defined in [includes/mailbox_graph.php](/code/files/includes/mailbox_graph.php.md), line 31.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$mailbox` |  |  | the target_mailboxes row, decrypted (azure_tenant_id, azure_client_id, azure_client_secret, token_data, id) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 68 | `mailboxAppOnlyToken` | **update** [target_mailboxes](/code/tables/target_mailboxes.md) (`token_data`) |  |

## Calls

[encryptValue()](/code/functions/encryptValue.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md)
- [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md)
- [api/tickets/verify_mailbox_folder.php](/code/files/api/tickets/verify_mailbox_folder.php.md)
- [includes/template_email.php](/code/files/includes/template_email.php.md) - in [templateGraphContext()](/code/functions/templateGraphContext.md)


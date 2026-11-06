# templateGraphContext()

Everything a Microsoft send needs for one mailbox: a valid access token and the Graph base path to send from. Returns null if no usable token could be obtained.

The two auth modes differ in BOTH halves, which is what made issue #67 subtle:

  delegated : a user signed in. token_data carries a refresh_token, and calls go
              to /me — Graph resolves "me" from the user inside the token.
  app_only  : client credentials. There is no user, so /me is meaningless and Graph
              rejects it with "/me request is only valid with delegated
              authentication flow" (HTTP 400). Calls go to /users/<target>, and
              there is no refresh_token either — the token is re-minted from the
              client secret.

Correcting only the path leaves a slower bug behind: `templateGetValidAccessToken()`
returns null for an app-only mailbox the moment its cached token expires, because it
looks for a refresh_token that client credentials never issue. That failure is masked
in normal use because the mail poller re-mints the cached token as a side effect of
reading — so sends work until polling stops or an hour passes, then fail with a
misleading "failed to get access token".

```php
function templateGraphContext(PDO $conn, array $mailbox): ?array
```

Defined in [includes/template_email.php](/code/files/includes/template_email.php.md), line 577.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$mailbox` | `array` |  | the target_mailboxes row, decrypted |

**Returns:** `?array`

## Calls

[mailboxAppOnlyToken()](/code/functions/mailboxAppOnlyToken.md), [mailboxIsAppOnly()](/code/functions/mailboxIsAppOnly.md), [mailboxResolveGraphBase()](/code/functions/mailboxResolveGraphBase.md), [templateGetValidAccessToken()](/code/functions/templateGetValidAccessToken.md)

## Called from

- [api/auth/request_password_reset.php](/code/files/api/auth/request_password_reset.php.md)
- [includes/mailbox_send.php](/code/files/includes/mailbox_send.php.md) - in [mailboxSendSimple()](/code/functions/mailboxSendSimple.md)
- [includes/self_service_email.php](/code/files/includes/self_service_email.php.md) - in [ssSendSystemEmail()](/code/functions/ssSendSystemEmail.md)
- [includes/template_email.php](/code/files/includes/template_email.php.md) - in [sendTemplateEmail()](/code/functions/sendTemplateEmail.md), [internalTicketEmail()](/code/functions/internalTicketEmail.md)
- [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) - in `WorkflowEngine::action_send_email`


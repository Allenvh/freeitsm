# templateSendViaGraph()

Send an email message via Microsoft Graph API.

`$graphBase` is '/me' (delegated) or '/users/<addr>' (app-only) — get it from
`templateGraphContext()`. It is deliberately REQUIRED rather than defaulting to '/me':
a default would reproduce exactly the silent wrong-endpoint bug this argument exists
to fix, and a missed caller should fail loudly at development time instead of quietly
sending from the wrong mailbox.

```php
function templateSendViaGraph(string $accessToken, array $message, string $graphBase): void
```

Defined in [includes/template_email.php](/code/files/includes/template_email.php.md), line 626.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$accessToken` | `string` |  | a valid Microsoft Graph access token, from `templateGraphContext()` |
| `$message` | `array` |  | the Graph sendMail body: ['message' => [...], 'saveToSentItems' => bool] |
| `$graphBase` | `string` |  | '/me' (delegated) or '/users/<address>' (app-only), from `templateGraphContext()` |

**Returns:** `void`

## Calls

[sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [includes/mailbox_send.php](/code/files/includes/mailbox_send.php.md) - in [mailboxSendSimple()](/code/functions/mailboxSendSimple.md)
- [includes/self_service_email.php](/code/files/includes/self_service_email.php.md) - in [ssSendSystemEmail()](/code/functions/ssSendSystemEmail.md)
- [includes/template_email.php](/code/files/includes/template_email.php.md) - in [sendTemplateEmail()](/code/functions/sendTemplateEmail.md), [internalTicketEmail()](/code/functions/internalTicketEmail.md)
- [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) - in `WorkflowEngine::action_send_email`


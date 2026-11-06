# sendEmailViaGraph()

Send email via Microsoft Graph API

```php
function sendEmailViaGraph($accessToken, $message)
```

Defined in [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md), line 627.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$accessToken` |  |  | the mailbox's Microsoft Graph access token, from `getValidAccessToken()` |
| `$message` |  |  | the sendMail request as built by `buildEmailMessage()` |

## Calls

[mailboxGraphBase()](/code/functions/mailboxGraphBase.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md)


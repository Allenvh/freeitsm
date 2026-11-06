# parseRecipients()

Parse recipients string into Graph API format

```php
function parseRecipients($recipientString)
```

Defined in [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md), line 598.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$recipientString` |  |  | addresses separated by ; or ,; invalid ones are dropped |

## Called from

- [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md) - in [buildEmailMessage()](/code/functions/buildEmailMessage.md)


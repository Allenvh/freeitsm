# stripThreadFromBody()

Strip the quoted thread from the email body Looks for the reply marker and returns only the content above it

```php
function stripThreadFromBody($body)
```

Defined in [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md), line 666.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$body` |  |  | an HTML body that may contain the reply marker and the quoted thread below it |

*Nothing calls this function by name.*


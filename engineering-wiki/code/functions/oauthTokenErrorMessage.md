# oauthTokenErrorMessage()

Turn a refused OAuth token response into something an administrator can act on.

🔴 WHY THIS EXISTS. Mail collection stopped on a live install and the only
thing FreeITSM said was "Failed to refresh token. HTTP Code: 401". The
provider had, in the very response body being thrown away, said exactly what
was wrong: "AADSTS7000222: The provided client secret keys for app '…' are
expired." One is a mystery costing an afternoon; the other is a five-minute
job in the Azure portal.

An expired client secret is not an unusual event — Azure secrets last at most
24 months and are routinely created with a 6- or 12-month life — so this is a
thing every install will eventually hit, and it must not present as "something
went wrong".

⚠️ The provider's text is passed through as-is rather than matched against a
list of known codes. A message we don't recognise is exactly the case where
the provider's own words are worth most, and a lookup table would replace it
with "Unknown error".

```php
function oauthTokenErrorMessage($response, int $httpCode): string
```

Defined in [includes/functions.php](/code/files/includes/functions.php.md), line 434.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$response` |  |  | the raw body from the token endpoint |
| `$httpCode` | `int` |  | the status it came back with |

**Returns:** `string`

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [refreshAccessToken()](/code/functions/refreshAccessToken.md)
- [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md) - in [refreshAccessToken()](/code/functions/refreshAccessToken.md)
- [api/tickets/verify_mailbox_folder.php](/code/files/api/tickets/verify_mailbox_folder.php.md)


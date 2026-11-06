# csrfSameOrigin()

Did this request come from this app's own pages? true / false, or null when the browser did not say (not a browser, or a very old one).

The scheme is deliberately NOT compared: behind a TLS-terminating proxy without
TRUST_PROXY_HTTPS, PHP sees http while the browser says https. Host and port
decide; an attacker cannot make a victim's browser claim this host.

```php
function csrfSameOrigin(): ?bool
```

Defined in [includes/csrf.php](/code/files/includes/csrf.php.md), line 180.

**Returns:** `?bool`

## Calls

[csrfOwnHosts()](/code/functions/csrfOwnHosts.md)

## Called from

- [includes/csrf.php](/code/files/includes/csrf.php.md) - in [csrfEnforce()](/code/functions/csrfEnforce.md), [csrfRequireToken()](/code/functions/csrfRequireToken.md)


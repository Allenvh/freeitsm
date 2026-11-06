# dockerTlsRequestIsHttps()

True when this request arrived over HTTPS, as PHP reports it in `$_SERVER`['HTTPS'].

```php
function dockerTlsRequestIsHttps(): bool
```

Defined in [includes/docker_tls.php](/code/files/includes/docker_tls.php.md), line 124.

**Returns:** `bool`

## Called from

- [includes/docker_tls.php](/code/files/includes/docker_tls.php.md) - in [dockerTlsBrowserAddress()](/code/functions/dockerTlsBrowserAddress.md)


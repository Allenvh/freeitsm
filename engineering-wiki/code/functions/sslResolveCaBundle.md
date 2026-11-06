# sslResolveCaBundle()

The CA bundle file to verify TLS with: PHP's configured one, else the bundled cacert.pem on Windows, else '' for the system store.

```php
function sslResolveCaBundle(): string
```

Defined in [includes/ssl.php](/code/files/includes/ssl.php.md), line 50.

**Returns:** `string`

## Called from

- [api/system/debug-tools/D006_ssl_verify.php](/code/files/api/system/debug-tools/D006_ssl_verify.php.md)
- [config.php](/code/files/config.php.md)
- [docker/config.php](/code/files/docker/config.php.md)
- [includes/domains/tls.php](/code/files/includes/domains/tls.php.md) - in [domainTlsCertificate()](/code/functions/domainTlsCertificate.md)
- [includes/ssl.php](/code/files/includes/ssl.php.md) - in [sslApplyCurl()](/code/functions/sslApplyCurl.md)


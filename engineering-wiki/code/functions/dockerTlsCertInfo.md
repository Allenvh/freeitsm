# dockerTlsCertInfo()

Read one PEM certificate's details, or null.

```php
function dockerTlsCertInfo(string $pem): ?array
```

Defined in [includes/docker_tls.php](/code/files/includes/docker_tls.php.md), line 145.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$pem` | `string` |  | one certificate in PEM text form |

**Returns:** `?array`

## Calls

[dockerTlsFingerprintNormalise()](/code/functions/dockerTlsFingerprintNormalise.md)

## Called from

- [includes/docker_tls.php](/code/files/includes/docker_tls.php.md) - in [dockerTlsState()](/code/functions/dockerTlsState.md), [dockerTlsNeedsNewCa()](/code/functions/dockerTlsNeedsNewCa.md), [dockerTlsGenerate()](/code/functions/dockerTlsGenerate.md)


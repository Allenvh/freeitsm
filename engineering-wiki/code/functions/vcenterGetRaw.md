# vcenterGetRaw()

Raw GET request - returns decoded JSON without unwrapping 'value' key so we can see the exact API response structure

```php
function vcenterGetRaw($baseUrl, $path, $sessionToken)
```

Defined in [api/assets/debug_vcenter.php](/code/files/api/assets/debug_vcenter.php.md), line 230.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$baseUrl` |  |  | the vCenter address with scheme and no trailing slash |
| `$path` |  |  | the API path to fetch, starting with a slash, e.g. /api/vcenter/vm |
| `$sessionToken` |  |  | the session id returned by `vcenterAuth()` |

## Calls

[sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [api/assets/debug_vcenter.php](/code/files/api/assets/debug_vcenter.php.md)


# vcenterGet()

GET request to vCenter REST API

```php
function vcenterGet($baseUrl, $path, $sessionToken)
```

Defined in [api/assets/get_vcenter.php](/code/files/api/assets/get_vcenter.php.md), line 302.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$baseUrl` |  |  | the vCenter address with scheme and no trailing slash |
| `$path` |  |  | the API path to fetch, starting with a slash; /api/ and /rest/ paths both work |
| `$sessionToken` |  |  | the session id returned by `vcenterAuth()` |

## Calls

[sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [api/assets/get_vcenter.php](/code/files/api/assets/get_vcenter.php.md)


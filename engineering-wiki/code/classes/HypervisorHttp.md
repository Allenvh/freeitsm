# HypervisorHttp

> Class in [includes/hypervisor_http.php](/code/files/includes/hypervisor_http.php.md), line 28

## Methods

| Method | What it does |
|---|---|
| [validateHost()](#validatehost) | Normalise and check a server address as typed: https://host or https://host:port, nothing after it. Throws a readable message. |
| [request()](#request) | One request. Returns [code, body, headers]. Throws only when the server could not be reached; an HTTP error status is returned for the caller to judge. |

### validateHost

*public static* - line 46

Normalise and check a server address as typed: https://host or https://host:port, nothing after it. Throws a readable message.

TRAP: keep this https-only. The credential travels with every request.

```php
function validateHost(string $host, string $example): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$host` | `string` |  | the server address as the administrator typed it; a trailing slash is dropped |
| `$example` | `string` |  | a sample address for the error message, e.g. 'https://pve.example.com:8006' |

**Called from:** [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md), [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md)

### request

*public static* - line 69

One request. Returns [code, body, headers]. Throws only when the server could not be reached; an HTTP error status is returned for the caller to judge.

```php
function request(
    string $method,
    string $url,
    array $headers,
    ?string $body,
    bool $verify,
    int $timeout = 60
): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$method` | `string` |  | the HTTP method, e.g. 'GET' or 'POST' |
| `$url` | `string` |  | the full URL; anything not https:// is refused |
| `$headers` | `array` |  | request headers as 'Name: value' strings, including the credential |
| `$body` | `?string` |  | the request body; null sends none |
| `$verify` | `bool` |  | false skips certificate checks - the server's "Verify certificate" box unticked |
| `$timeout` | `int` | `60` | the whole request's time limit, in seconds |

**Called from:** [includes/proxmox_sync.php](/code/files/includes/proxmox_sync.php.md), [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md)


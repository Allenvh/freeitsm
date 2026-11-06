# dockerTlsBrowserAddress()

What the browser used to reach this page: host, port and whether host is an IP.

🔑 THIS IS HOW THE SCREEN KNOWS THE IP. Inside a container PHP only sees its
own internal address (172.x), which is no use to anybody. The address in the
browser's address bar is the one the PCs actually reach.

```php
function dockerTlsBrowserAddress(): array
```

Defined in [includes/docker_tls.php](/code/files/includes/docker_tls.php.md), line 101.

**Returns:** `array`

## Calls

[dockerTlsRequestIsHttps()](/code/functions/dockerTlsRequestIsHttps.md)

## Called from

- [system/docker/index.php](/code/files/system/docker/index.php.md)


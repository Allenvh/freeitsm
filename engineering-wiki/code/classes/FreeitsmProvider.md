# FreeitsmProvider

> Class in [includes/messaging/FreeitsmProvider.php](/code/files/includes/messaging/FreeitsmProvider.php.md), line 20

## Methods

| Method | What it does |
|---|---|
| [verifyWebhook()](#verifywebhook) | Webchat has its own public endpoints, so the shared webhook never routes here. |
| [parseInbound()](#parseinbound) | Not used — inbound is handled by api/webchat/send.php, not parseInbound. |
| [sendMessage()](#sendmessage) | "Send" an outbound reply. There is nothing to transmit — the message row is stored by the caller and delivered when the visitor next polls — so we only mint a stable id. Prefixed 'wc_out_' so it's recognisable in the emails table. |
| [testConnection()](#testconnection) | Nothing to reach; the channel is healthy as long as it exists and is active. |

### verifyWebhook

*public* - line 30

Webchat has its own public endpoints, so the shared webhook never routes here.

```php
function verifyWebhook(string $rawBody, array $headers, array $params, string $url): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rawBody` | `string` |  | the raw request body (ignored) |
| `$headers` | `array` |  | the request headers (ignored) |
| `$params` | `array` |  | the parsed POST parameters (ignored) |
| `$url` | `string` |  | the public URL that was hit (ignored) |

### parseInbound

*public* - line 41

Not used — inbound is handled by api/webchat/send.php, not parseInbound.

```php
function parseInbound(string $rawBody, array $params): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rawBody` | `string` |  | the raw request body (ignored) |
| `$params` | `array` |  | the parsed POST parameters (ignored) |

### sendMessage

*public* - line 54

"Send" an outbound reply. There is nothing to transmit — the message row is stored by the caller and delivered when the visitor next polls — so we only mint a stable id. Prefixed 'wc_out_' so it's recognisable in the emails table.

```php
function sendMessage(string $to, string $body): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$to` | `string` |  | the web chat visitor's address (ignored - the caller stores the message) |
| `$body` | `string` |  | the reply text (ignored - the caller stores the message) |

### testConnection

*public* - line 60

Nothing to reach; the channel is healthy as long as it exists and is active.

```php
function testConnection(): string
```


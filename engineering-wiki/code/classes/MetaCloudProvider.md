# MetaCloudProvider

> Class in [includes/messaging/MetaCloudProvider.php](/code/files/includes/messaging/MetaCloudProvider.php.md), line 21

## Methods

| Method | What it does |
|---|---|
| [verifyChallenge()](#verifychallenge) | Answers Meta's webhook subscription check: returns the challenge when the verify token matches, else null. |
| [verifyWebhook()](#verifywebhook) | Checks the X-Hub-Signature-256 HMAC of the raw body against the app secret. |
| [parseInbound()](#parseinbound) | Turns a WhatsApp Cloud API webhook into inbound messages - text, or media to download later with the caption as the body. |
| [sendMessage()](#sendmessage) | Sends a WhatsApp text message through the Graph API and returns Meta's message id. |
| [sendMedia()](#sendmedia) | Two Graph API calls: upload the bytes to get a media id, then send a message referencing it. `$filePath` is used directly (multipart upload); `$publicUrl` is Twilio's requirement, not Meta's, and is ignored here. |
| [sendTemplate()](#sendtemplate) | Sends an approved WhatsApp template, with `$vars` as the body parameters, and returns Meta's message id. |
| [downloadMedia()](#downloadmedia) | Downloads an inbound media item: resolves its id to a URL, then fetches the bytes with the access token. |
| [testConnection()](#testconnection) | Checks the phone number id and access token by reading the number from the Graph API, sending nothing. |
| [ensurePlus()](#ensureplus) | Puts a single leading + on a phone number; blank stays blank. |

### verifyChallenge

*public* - line 34

Answers Meta's webhook subscription check: returns the challenge when the verify token matches, else null.

```php
function verifyChallenge(array $get): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$get` | `array` |  | the request's query parameters - hub.mode, hub.verify_token, hub.challenge (PHP may turn the dots into _) |

### verifyWebhook

*public* - line 54

Checks the X-Hub-Signature-256 HMAC of the raw body against the app secret.

```php
function verifyWebhook(string $rawBody, array $headers, array $params, string $url): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rawBody` | `string` |  | the raw request body, exactly as received - the HMAC is over these bytes |
| `$headers` | `array` |  | the request headers, lower-cased keys (x-hub-signature-256) |
| `$params` | `array` |  | the parsed POST parameters (not used) |
| `$url` | `string` |  | the public URL that was hit (not used) |

### parseInbound

*public* - line 72

Turns a WhatsApp Cloud API webhook into inbound messages - text, or media to download later with the caption as the body.

```php
function parseInbound(string $rawBody, array $params): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rawBody` | `string` |  | the raw JSON webhook body from Meta |
| `$params` | `array` |  | the parsed POST parameters (not used) |

### sendMessage

*public* - line 126

Sends a WhatsApp text message through the Graph API and returns Meta's message id.

```php
function sendMessage(string $to, string $body): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$to` | `string` |  | the recipient's WhatsApp number, with or without the leading + |
| `$body` | `string` |  | the message text |

### sendMedia

*public* - line 169

Two Graph API calls: upload the bytes to get a media id, then send a message referencing it. `$filePath` is used directly (multipart upload); `$publicUrl` is Twilio's requirement, not Meta's, and is ignored here.

```php
function sendMedia(
    string $to,
    string $filePath,
    string $mimeType,
    string $caption = '',
    string $publicUrl = '',
    string $filename = ''
): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$to` | `string` |  | the recipient's WhatsApp number, with or without the leading + |
| `$filePath` | `string` |  | the file on this server's disk, already validated |
| `$mimeType` | `string` |  | the file's MIME type - also decides image, video, audio or document |
| `$caption` | `string` | `''` | text sent with the file; '' for none, and never sent with audio |
| `$publicUrl` | `string` | `''` | not used - Meta takes an upload |
| `$filename` | `string` | `''` | the name the recipient sees on a document; '' uses the stored name |

### sendTemplate

*public* - line 242

Sends an approved WhatsApp template, with `$vars` as the body parameters, and returns Meta's message id.

Needed to message someone outside the 24-hour service window.

```php
function sendTemplate(string $to, array $template, array $vars): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$to` | `string` |  | the recipient's WhatsApp number, with or without the leading + |
| `$template` | `array` |  | a messaging_templates row (provider_ref = the Meta template name, language) |
| `$vars` | `array` |  | ordered values for the template's {{1}}, {{2}}, ... |

### downloadMedia

*public* - line 295

Downloads an inbound media item: resolves its id to a URL, then fetches the bytes with the access token.

```php
function downloadMedia(array $item): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$item` | `array` |  | one entry from `parseInbound()`'s 'media' list (id, content_type, filename) |

### testConnection

*public* - line 336

Checks the phone number id and access token by reading the number from the Graph API, sending nothing.

```php
function testConnection(): string
```

### ensurePlus

*private* - line 367

Puts a single leading + on a phone number; blank stays blank.

```php
function ensurePlus(string $number): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$number` | `string` |  | a phone number, with or without a leading + |


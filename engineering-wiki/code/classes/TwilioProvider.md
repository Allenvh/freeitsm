# TwilioProvider

> Class in [includes/messaging/TwilioProvider.php](/code/files/includes/messaging/TwilioProvider.php.md), line 21

## Methods

| Method | What it does |
|---|---|
| [verifyWebhook()](#verifywebhook) | Checks the X-Twilio-Signature header: an HMAC-SHA1 of the URL and the sorted POST fields, keyed with the auth token. |
| [parseInbound()](#parseinbound) | Turns a Twilio WhatsApp webhook into an inbound message with its media URLs; status callbacks give nothing. |
| [sendMessage()](#sendmessage) | Sends a WhatsApp text message through Twilio and returns the message SID. |
| [sendMedia()](#sendmedia) | Twilio's Messages API takes no upload at all — it fetches the media itself from a URL you give it (MediaUrl), fire-and-forget, sometime after this call returns. `$publicUrl` (messagingOutboundMediaUrl()) is therefore REQUIRED here, unlike Meta/Telegram which take the bytes directly; `$filePath` is unused. |
| [sendTemplate()](#sendtemplate) | Sends an approved WhatsApp template by its Content SID, with `$vars` as numbered content variables, and returns the message SID. |
| [downloadMedia()](#downloadmedia) | Downloads an inbound media item with account authentication, naming the file after its Media SID. |
| [testConnection()](#testconnection) | Checks the Account SID and Auth Token by reading the account, sending nothing; notes when no WhatsApp number is set. |
| [stripPrefix()](#stripprefix) | Drop Twilio's "whatsapp:" channel prefix, leaving the bare number. |
| [ensurePlus()](#ensureplus) | Puts a single leading + on a phone number. |

### verifyWebhook

*public* - line 32

Checks the X-Twilio-Signature header: an HMAC-SHA1 of the URL and the sorted POST fields, keyed with the auth token.

```php
function verifyWebhook(string $rawBody, array $headers, array $params, string $url): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rawBody` | `string` |  | the raw request body (not used - Twilio signs the parsed fields) |
| `$headers` | `array` |  | the request headers, lower-cased keys - x-twilio-signature is checked |
| `$params` | `array` |  | the parsed POST fields, all part of the signed string |
| `$url` | `string` |  | the full public URL Twilio called, exactly as configured - it is part of the signed string |

### parseInbound

*public* - line 57

Turns a Twilio WhatsApp webhook into an inbound message with its media URLs; status callbacks give nothing.

```php
function parseInbound(string $rawBody, array $params): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rawBody` | `string` |  | the raw request body (not used) |
| `$params` | `array` |  | the parsed POST fields (From, To, Body, MessageSid, ProfileName, NumMedia, MediaUrl{n}, ...) |

### sendMessage

*public* - line 95

Sends a WhatsApp text message through Twilio and returns the message SID.

```php
function sendMessage(string $to, string $body): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$to` | `string` |  | the recipient's WhatsApp number, with or without the leading + |
| `$body` | `string` |  | the message text |

### sendMedia

*public* - line 140

Twilio's Messages API takes no upload at all — it fetches the media itself from a URL you give it (MediaUrl), fire-and-forget, sometime after this call returns. `$publicUrl` (messagingOutboundMediaUrl()) is therefore REQUIRED here, unlike Meta/Telegram which take the bytes directly; `$filePath` is unused.

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
| `$filePath` | `string` |  | not used - Twilio fetches the file from `$publicUrl` |
| `$mimeType` | `string` |  | the file's MIME type (not sent - Twilio reads it from the download) |
| `$caption` | `string` | `''` | text sent with the file; '' for none |
| `$publicUrl` | `string` | `''` | a public URL Twilio can fetch the file from - required |
| `$filename` | `string` | `''` | not used - Twilio names the file itself |

### sendTemplate

*public* - line 186

Sends an approved WhatsApp template by its Content SID, with `$vars` as numbered content variables, and returns the message SID.

```php
function sendTemplate(string $to, array $template, array $vars): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$to` | `string` |  | the recipient's WhatsApp number, with or without the leading + |
| `$template` | `array` |  | a messaging_templates row - provider_ref is the Twilio Content SID |
| `$vars` | `array` |  | ordered values for the template's {{1}}, {{2}}, ... |

### downloadMedia

*public* - line 235

Downloads an inbound media item with account authentication, naming the file after its Media SID.

```php
function downloadMedia(array $item): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$item` | `array` |  | one entry from `parseInbound()`'s 'media' list (url, content_type) |

### testConnection

*public* - line 270

Checks the Account SID and Auth Token by reading the account, sending nothing; notes when no WhatsApp number is set.

```php
function testConnection(): string
```

### stripPrefix

*private* - line 308

Drop Twilio's "whatsapp:" channel prefix, leaving the bare number.

```php
function stripPrefix(string $addr): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$addr` | `string` |  | a Twilio address, e.g. 'whatsapp:+447700900123' |

### ensurePlus

*private* - line 322

Puts a single leading + on a phone number.

```php
function ensurePlus(string $number): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$number` | `string` |  | a phone number, with or without a leading + |


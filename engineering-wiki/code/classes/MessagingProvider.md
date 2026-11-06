# MessagingProvider

> Class in [includes/messaging/MessagingProvider.php](/code/files/includes/messaging/MessagingProvider.php.md), line 23

MessagingProvider — the provider-agnostic contract for a chat channel (WhatsApp today; SMS/others later). Everything above this line — webhook ingestion, ticket creation, the reply path — talks to this interface and never knows whether Twilio or Meta Cloud is actually live.

Concrete providers: TwilioProvider, MetaCloudProvider. They are constructed
with a decrypted messaging_channels row (see messaging.php → `messagingProvider()`).

A "normalised inbound message" (the shape parseInbound returns) is:
  [
    'from'           => '+447700900123',   // sender, normalised (+ and digits)
    'to'             => '+14155238886',    // the business number it arrived on
    'body'           => 'Hi, my laptop…',  // plain text
    'profile_name'   => 'Jane Doe',        // sender display name, or ''
    'provider_msg_id'=> 'SMxxxx',          // provider's message id (dedupe)
    'media'          => [ ['url'=>…, 'content_type'=>…], … ],
    'timestamp'      => 1719500000,        // unix seconds, or null
  ]

## Methods

| Method | What it does |
|---|---|
| [__construct()](#__construct) | Keeps the decrypted messaging_channels row the provider works with. |
| [getType()](#gettype) | Channel type this instance serves, e.g. 'whatsapp'. |
| [verifyWebhook()](#verifywebhook) | Verify an inbound webhook is genuinely from the provider (signature / shared-secret check). Return false to reject — the endpoint will 403. |
| [verifyChallenge()](#verifychallenge) | Meta-style GET verification handshake (hub.challenge). Return the challenge string to echo, or null if this provider/request doesn't use it (Twilio). |
| [parseInbound()](#parseinbound) | Parse an inbound webhook into zero or more normalised messages (see the file header for the shape). Returns [] for non-message events (status callbacks, etc.) so the caller can simply skip them. |
| [sendMessage()](#sendmessage) | Send a free-text message to a recipient (within the 24h service window). Returns the provider's message id. Throws on failure. |
| [sendTemplate()](#sendtemplate) | Send a pre-approved template message (the 24h-window escape hatch). |
| [sendRatingRequest()](#sendratingrequest) | Ask the customer to rate the service 1–5 (CSAT). Default: a plain message saying to reply with one digit, which the webhook then records (see `csatPendingRequestForChat()`). A channel with real buttons overrides this to show them; `$responseId` ties a button press back to its csat row. |
| [sendMedia()](#sendmedia) | Send a single local file (image, document, …) to a recipient — the outbound twin of `downloadMedia()`. Returns the provider's message id. Throws on failure, and by default for any provider that doesn't support this yet. |
| [testConnection()](#testconnection) | Verify the channel's credentials against the provider with a lightweight, read-only API call (no message is sent). Returns a short human-readable success detail (e.g. the account/number it reached). Throws on failure with a message suitable for showing the analyst. |
| [downloadMedia()](#downloadmedia) | Download one inbound media item (an entry from parseInbound's 'media' array). Returns ['data' => binary, 'content_type' => string, 'filename' => string]. Throws on failure. |
| [httpRequest()](#httprequest) | Shared cURL helper. Returns [httpCode, bodyString]. No exceptions on HTTP error codes — the caller decides what a bad status means. |
| [httpMultipart()](#httpmultipart) | POST multipart/form-data — for uploading a file to a provider. Returns [httpCode, bodyString], like `httpRequest()`. |
| [outboundFilename()](#outboundfilename) | The name a recipient sees for an outbound file: the given one, else the stored one. |

### __construct

*public* - line 33

Keeps the decrypted messaging_channels row the provider works with.

```php
function __construct(array $channel)
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$channel` | `array` |  | the messaging_channels row, decrypted (credentials already a PHP array) |

### getType

*public* - line 39

Channel type this instance serves, e.g. 'whatsapp'.

```php
function getType(): string
```

### verifyWebhook

*public* - line 53

Verify an inbound webhook is genuinely from the provider (signature / shared-secret check). Return false to reject — the endpoint will 403.

```php
function verifyWebhook(string $rawBody, array $headers, array $params, string $url): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rawBody` | `string` |  | raw request body |
| `$headers` | `array` |  | request headers, lower-cased keys |
| `$params` | `array` |  | parsed POST params (form providers like Twilio) |
| `$url` | `string` |  | the full public URL the provider hit (Twilio signs it) |

### verifyChallenge

*public* - line 61

Meta-style GET verification handshake (hub.challenge). Return the challenge string to echo, or null if this provider/request doesn't use it (Twilio).

```php
function verifyChallenge(array $get): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$get` | `array` |  | the request's query parameters (`$_GET`) |

### parseInbound

*public* - line 74

Parse an inbound webhook into zero or more normalised messages (see the file header for the shape). Returns [] for non-message events (status callbacks, etc.) so the caller can simply skip them.

```php
function parseInbound(string $rawBody, array $params): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rawBody` | `string` |  | the raw request body |
| `$params` | `array` |  | the parsed POST parameters (form-posting providers such as Twilio) |

### sendMessage

*public* - line 83

Send a free-text message to a recipient (within the 24h service window). Returns the provider's message id. Throws on failure.

```php
function sendMessage(string $to, string $body): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$to` | `string` |  | the recipient, in the provider's address form (e.g. '+447700900123') |
| `$body` | `string` |  | the plain-text message |

### sendTemplate

*public* - line 93

Send a pre-approved template message (the 24h-window escape hatch).

```php
function sendTemplate(string $to, array $template, array $vars): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$to` | `string` |  | recipient, '+digits' |
| `$template` | `array` |  | a messaging_templates row (provider_ref, language, body…) |
| `$vars` | `array` |  | ordered placeholder values for {{1}}, {{2}}, … |

**Returns:** string provider message id

### sendRatingRequest

*public* - line 108

Ask the customer to rate the service 1–5 (CSAT). Default: a plain message saying to reply with one digit, which the webhook then records (see `csatPendingRequestForChat()`). A channel with real buttons overrides this to show them; `$responseId` ties a button press back to its csat row.

```php
function sendRatingRequest(string $to, string $text, int $responseId): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$to` | `string` |  | the recipient, in the provider's address form |
| `$text` | `string` |  | the question to send |
| `$responseId` | `int` |  | the ticket_csat_responses id a button press is tied back to |

### sendMedia

*public* - line 143

Send a single local file (image, document, …) to a recipient — the outbound twin of `downloadMedia()`. Returns the provider's message id. Throws on failure, and by default for any provider that doesn't support this yet.

Two ways to reach the file, because providers disagree on which they
need:
  `$filePath`  — a path on THIS server's disk (already validated — see
               `uploadStoreFile()`). Meta and Telegram upload these
               bytes directly (multipart).
  `$publicUrl` — a short-lived, unguessable URL to the SAME file
               (messagingOutboundMediaUrl()). Twilio's API takes no
               upload at all — it fetches the media itself from a URL
               you give it — so a provider that needs this throws if
               it's blank rather than silently sending nothing.
A provider uses whichever it needs and ignores the other.

  `$filename`  — the name the RECIPIENT sees. On disk the file has our own
               random name (uploadStoreFile()), which is right for the
               server and meaningless to a customer. Blank falls back to
               the stored name.

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
| `$to` | `string` |  | the recipient, in the provider's address form |
| `$filePath` | `string` |  | the file on this server's disk, already validated |
| `$mimeType` | `string` |  | the file's MIME type |
| `$caption` | `string` | `''` | text sent with the file; '' for none |
| `$publicUrl` | `string` | `''` | a short-lived URL to the same file, for providers that fetch it themselves; '' if none |
| `$filename` | `string` | `''` | the name the recipient sees; '' uses the stored name |

### testConnection

*public* - line 154

Verify the channel's credentials against the provider with a lightweight, read-only API call (no message is sent). Returns a short human-readable success detail (e.g. the account/number it reached). Throws on failure with a message suitable for showing the analyst.

```php
function testConnection(): string
```

### downloadMedia

*public* - line 166

Download one inbound media item (an entry from parseInbound's 'media' array). Returns ['data' => binary, 'content_type' => string, 'filename' => string]. Throws on failure.

```php
function downloadMedia(array $item): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$item` | `array` |  | one entry from `parseInbound()`'s 'media' list |

### httpRequest

*protected* - line 181

Shared cURL helper. Returns [httpCode, bodyString]. No exceptions on HTTP error codes — the caller decides what a bad status means.

```php
function httpRequest(string $url, array $opts = []): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$url` | `string` |  | the full URL to call |
| `$opts` | `array` | `[]` | ['method'=>'POST', 'headers'=>[], 'body'=>string, 'auth'=>'user:pass'] |

### httpMultipart

*protected* - line 238

POST multipart/form-data — for uploading a file to a provider. Returns [httpCode, bodyString], like `httpRequest()`.

Separate from `httpRequest()` because a multipart upload is the one shape that
helper cannot express: it always sends a STRING body, and a file upload needs
cURL to build the body AND the boundary header itself from an ARRAY of
fields (a CURLFile among them). Do not set a Content-Type header here; cURL
must write it, boundary included.

PR #159 wrote this out twice (Telegram and Meta). One copy means the SSL
setting, timeout and error wording cannot drift between providers.

```php
function httpMultipart(string $url, array $fields, array $headers = []): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$url` | `string` |  | the full URL to POST to |
| `$fields` | `array` |  | form fields; a file is a CURLFile |
| `$headers` | `array` | `[]` | extra headers, e.g. ["Authorization: Bearer …"] |

### outboundFilename

*protected* - line 266

The name a recipient sees for an outbound file: the given one, else the stored one.

```php
function outboundFilename(string $filePath, string $filename): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$filePath` | `string` |  | the file's path on disk; its base name is the fallback |
| `$filename` | `string` |  | the name asked for; CR, LF and double quotes are stripped |


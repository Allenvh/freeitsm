# MattermostProvider

> Class in [includes/messaging/MattermostProvider.php](/code/files/includes/messaging/MattermostProvider.php.md), line 36

## Methods

| Method | What it does |
|---|---|
| [verifyWebhook()](#verifywebhook) | Checks an inbound webhook: a CSAT button press must carry a valid signature, anything else the channel's webhook token. |
| [parseInbound()](#parseinbound) | Turns a Mattermost webhook into inbound messages - a CSAT rating press, or a post in the channel. |
| [sendMessage()](#sendmessage) | Posts a text reply in the conversation's thread and returns the post id. |
| [sendRatingRequest()](#sendratingrequest) | The rating question with five buttons, in the customer's thread. Each button calls this install's webhook with its context, signed with the webhook token, so a press cannot be forged. |
| [csatSignature()](#csatsignature) | HMAC over the response id and rating, keyed by the webhook token. |
| [buttonCallbackUrl()](#buttoncallbackurl) | This channel's webhook URL, the one Mattermost calls back for button presses. |
| [sendMedia()](#sendmedia) | Upload the file to the support channel, then post it in the customer's thread. |
| [testConnection()](#testconnection) | Checks the bot token by reading the bot user, and returns a "Connected as `@name`" message. |
| [splitAddress()](#splitaddress) | "<channelId>:<postId>" -> [channelId, postId]. |
| [postInThread()](#postinthread) | Post into the thread the address names, under that thread's root. |
| [rootOf()](#rootof) | The root of the thread a post is in. TRAP: Mattermost refuses a root_id that is itself a reply, and the outgoing webhook never says which a post is - so ask, rather than threading under whatever post came in last. |
| [webhookField()](#webhookfield) | A field from the webhook body: form-encoded (Mattermost's default) or JSON. |
| [serverUrl()](#serverurl) | The channel's Mattermost server address without a trailing slash; throws when it is missing or not http(s). |
| [apiUrl()](#apiurl) | A Mattermost API v4 address for `$path`. |
| [authHeader()](#authheader) | The bearer Authorization header for the bot token; throws when there is no token. |
| [api()](#api) | A JSON REST call. Throws with Mattermost's own message on failure. |
| [lookupUser()](#lookupuser) | Who a Mattermost user is: ['name' => …, 'email' => …], either may be ''. Used to file their tickets under the person FreeITSM already knows - see `messagingResolveRequesterByEmail()` in ingest.php. Never throws. |
| [botIdOrEmpty()](#botidorempty) | The bot's own user id, or '' if Mattermost cannot be asked right now (then nothing is dropped). |
| [setBotIdForTest()](#setbotidfortest) | Test hook: set the bot id without asking a server. |

### verifyWebhook

*public* - line 53

Checks an inbound webhook: a CSAT button press must carry a valid signature, anything else the channel's webhook token.

Refuses everything when the channel has no token yet.

```php
function verifyWebhook(string $rawBody, array $headers, array $params, string $url): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rawBody` | `string` |  | the raw request body - JSON for a button press, else form-encoded or JSON |
| `$headers` | `array` |  | the request headers (not used - Mattermost sends its token in the body) |
| `$params` | `array` |  | the parsed POST parameters, where the webhook token normally arrives |
| `$url` | `string` |  | the public URL that was hit (not used) |

### parseInbound

*public* - line 76

Turns a Mattermost webhook into inbound messages - a CSAT rating press, or a post in the channel.

```php
function parseInbound(string $rawBody, array $params): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rawBody` | `string` |  | the raw request body - JSON for a button press, else form-encoded or JSON |
| `$params` | `array` |  | the parsed POST parameters (form-encoded outgoing webhook fields) |

### sendMessage

*public* - line 141

Posts a text reply in the conversation's thread and returns the post id.

```php
function sendMessage(string $to, string $body): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$to` | `string` |  | the reply address "<channelId>:<postId>" - the thread to answer in |
| `$body` | `string` |  | the reply text |

### sendRatingRequest

*public* - line 155

The rating question with five buttons, in the customer's thread. Each button calls this install's webhook with its context, signed with the webhook token, so a press cannot be forged.

```php
function sendRatingRequest(string $to, string $text, int $responseId): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$to` | `string` |  | the reply address "<channelId>:<postId>" |
| `$text` | `string` |  | the rating question |
| `$responseId` | `int` |  | the ticket_csat_responses id each button carries back |

### csatSignature

*private* - line 179

HMAC over the response id and rating, keyed by the webhook token.

```php
function csatSignature(array $context): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$context` | `array` |  | the button context - csat_response_id and csat_rating are signed |

### buttonCallbackUrl

*private* - line 186

This channel's webhook URL, the one Mattermost calls back for button presses.

```php
function buttonCallbackUrl(): string
```

### sendMedia

*public* - line 202

Upload the file to the support channel, then post it in the customer's thread.

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
| `$to` | `string` |  | the reply address "<channelId>:<postId>" |
| `$filePath` | `string` |  | the file on this server's disk, already validated |
| `$mimeType` | `string` |  | the file's MIME type |
| `$caption` | `string` | `''` | the message posted with the file; '' for none |
| `$publicUrl` | `string` | `''` | not used - the file is uploaded to Mattermost |
| `$filename` | `string` | `''` | the name the customer sees; '' uses the stored name |

### testConnection

*public* - line 222

Checks the bot token by reading the bot user, and returns a "Connected as `@name`" message.

```php
function testConnection(): string
```

### splitAddress

*private* - line 239

"<channelId>:<postId>" -> [channelId, postId].

```php
function splitAddress(string $to): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$to` | `string` |  | the reply address "<channelId>:<postId>"; both parts must be 26-character Mattermost ids |

### postInThread

*private* - line 256

Post into the thread the address names, under that thread's root.

```php
function postInThread(string $to, array $fields): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$to` | `string` |  | the reply address "<channelId>:<postId>" |
| `$fields` | `array` |  | the post fields to send (message, props, file_ids); channel_id and root_id are added here |

### rootOf

*private* - line 270

The root of the thread a post is in. TRAP: Mattermost refuses a root_id that is itself a reply, and the outgoing webhook never says which a post is - so ask, rather than threading under whatever post came in last.

```php
function rootOf(string $postId): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$postId` | `string` |  | the Mattermost post id the address names |

### webhookField

*private* - line 287

A field from the webhook body: form-encoded (Mattermost's default) or JSON.

```php
function webhookField(string $rawBody, array $params, string $key): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rawBody` | `string` |  | the raw request body, read as JSON when the field is not in `$params` |
| `$params` | `array` |  | the parsed POST parameters, checked first |
| `$key` | `string` |  | the field name, e.g. 'token' or 'channel_id' |

### serverUrl

*private* - line 300

The channel's Mattermost server address without a trailing slash; throws when it is missing or not http(s).

```php
function serverUrl(): string
```

### apiUrl

*private* - line 314

A Mattermost API v4 address for `$path`.

```php
function apiUrl(string $path): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$path` | `string` |  | the API path after /api/v4/, e.g. 'posts' |

### authHeader

*private* - line 320

The bearer Authorization header for the bot token; throws when there is no token.

```php
function authHeader(): string
```

### api

*private* - line 336

A JSON REST call. Throws with Mattermost's own message on failure.

```php
function api(string $method, string $path, $body = null): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$method` | `string` |  | the HTTP method, e.g. 'GET' or 'POST' |
| `$path` | `string` |  | the API path after /api/v4/ |
| `$body` |  | `null` | data sent as the JSON body; null sends none |

### lookupUser

*public* - line 367

Who a Mattermost user is: ['name' => …, 'email' => …], either may be ''. Used to file their tickets under the person FreeITSM already knows - see `messagingResolveRequesterByEmail()` in ingest.php. Never throws.

TRAP: the email is returned ONLY when Mattermost says it is verified.
  A server can allow sign-up without proving the address, and an
  unverified email is just text anyone typed: someone registering as
  ceo@yourcompany.com would have their chats filed under the real CEO.
  An empty email (the server hides addresses from this bot) is normal -
  the person simply gets a named contact instead.

```php
function lookupUser(string $userId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$userId` | `string` |  | the Mattermost user id |

### botIdOrEmpty

*private* - line 384

The bot's own user id, or '' if Mattermost cannot be asked right now (then nothing is dropped).

```php
function botIdOrEmpty(): string
```

### setBotIdForTest

*public static* - line 403

Test hook: set the bot id without asking a server.

```php
function setBotIdForTest(int $channelId, string $botId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$channelId` | `int` |  | the messaging_channels id |
| `$botId` | `string` |  | the bot user id to remember for that channel |


# TeamsProvider

> Class in [includes/messaging/TeamsProvider.php](/code/files/includes/messaging/TeamsProvider.php.md), line 59

## Methods

| Method | What it does |
|---|---|
| [verifyWebhook()](#verifywebhook) | Checks an inbound Bot Framework activity: the bearer JWT's signature, issuer and audience (the app id), and that its service URL is an allowed Microsoft host matching the token. |
| [hostAllowed()](#hostallowed) | An https URL whose host is one of `$hosts` (exact, or a suffix starting with a dot). |
| [splitAddress()](#splitaddress) | "<serviceUrl>\|<conversationId>" -> [serviceUrl, conversationId]. A bare id uses the default region. |
| [parseInbound()](#parseinbound) | Turns a Teams activity into inbound messages - a CSAT rating press or a message in a one-to-one chat. |
| [sendRatingRequest()](#sendratingrequest) | The rating question as an Adaptive Card with five buttons, 1 to 5. A press comes back as a message whose value carries csat.r (response id) and csat.v (rating). |
| [sendMessage()](#sendmessage) | Sends a text reply into the Teams conversation and returns the activity id. |
| [sendMedia()](#sendmedia) | Teams shows an image sent as an attachment with contentUrl. That URL must be a public https address — `messagingOutboundMediaUrl()` provides one, which is why images are the only kind this sends. Other files are refused with a plain message, not sent as a broken attachment. |
| [downloadMedia()](#downloadmedia) | Inbound images: fetch with the bot's token — Teams does not serve them anonymously. |
| [testConnection()](#testconnection) | Checks the bot credentials by getting an access token, and returns the app id. |
| [appId()](#appid) | The channel's Microsoft app (bot) id. |
| [accessToken()](#accesstoken) | Azure AD client-credentials token for the Bot Framework scope. |
| [lookupUser()](#lookupuser) | Who a Teams user is: ['name' => …, 'email' => …], either may be ''. Bot Framework's "get conversation member" - the bot's own token, no Microsoft Graph permission. Used to file their tickets under the person FreeITSM already knows (messagingResolveRequesterByEmail()). Never throws. |
| [postActivity()](#postactivity) | Posts an activity to a conversation through its Bot Framework service URL and returns the new activity id. |
| [botFrameworkKeys()](#botframeworkkeys) | Bot Framework's published signing keys, cached for a day. The key set is public and rotates rarely, so a cache file holds nothing secret. |

### verifyWebhook

*public* - line 92

Checks an inbound Bot Framework activity: the bearer JWT's signature, issuer and audience (the app id), and that its service URL is an allowed Microsoft host matching the token.

```php
function verifyWebhook(string $rawBody, array $headers, array $params, string $url): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rawBody` | `string` |  | the raw JSON activity, read for serviceUrl and the sender's tenant |
| `$headers` | `array` |  | the request headers, lower-cased keys - 'authorization' carries the Bot Framework JWT |
| `$params` | `array` |  | the parsed POST parameters (not used) |
| `$url` | `string` |  | the public URL that was hit (not used) |

### hostAllowed

*private static* - line 135

An https URL whose host is one of `$hosts` (exact, or a suffix starting with a dot).

```php
function hostAllowed(string $url, array $hosts): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$url` | `string` |  | the URL to check; only https passes |
| `$hosts` | `array` |  | allowed hosts - an exact name, or a suffix starting with a dot |

### splitAddress

*private static* - line 155

"<serviceUrl>|<conversationId>" -> [serviceUrl, conversationId]. A bare id uses the default region.

```php
function splitAddress(string $to): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$to` | `string` |  | the reply address "<serviceUrl>\|<conversationId>", or a bare conversation id |

### parseInbound

*public* - line 174

Turns a Teams activity into inbound messages - a CSAT rating press or a message in a one-to-one chat.

Group chats and channels are ignored, since one conversation there is shared by many
people.

```php
function parseInbound(string $rawBody, array $params): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rawBody` | `string` |  | the raw JSON Bot Framework activity |
| `$params` | `array` |  | the parsed POST parameters (not used) |

### sendRatingRequest

*public* - line 250

The rating question as an Adaptive Card with five buttons, 1 to 5. A press comes back as a message whose value carries csat.r (response id) and csat.v (rating).

```php
function sendRatingRequest(string $to, string $text, int $responseId): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$to` | `string` |  | the reply address "<serviceUrl>\|<conversationId>" |
| `$text` | `string` |  | the rating question |
| `$responseId` | `int` |  | the ticket_csat_responses id each button carries back |

### sendMessage

*public* - line 281

Sends a text reply into the Teams conversation and returns the activity id.

```php
function sendMessage(string $to, string $body): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$to` | `string` |  | the reply address "<serviceUrl>\|<conversationId>"; '' throws |
| `$body` | `string` |  | the reply text |

### sendMedia

*public* - line 302

Teams shows an image sent as an attachment with contentUrl. That URL must be a public https address — `messagingOutboundMediaUrl()` provides one, which is why images are the only kind this sends. Other files are refused with a plain message, not sent as a broken attachment.

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
| `$to` | `string` |  | the reply address "<serviceUrl>\|<conversationId>" |
| `$filePath` | `string` |  | the file on this server's disk; only its name is used |
| `$mimeType` | `string` |  | the file's MIME type - anything but image/* is refused |
| `$caption` | `string` | `''` | text sent with the image; '' for none |
| `$publicUrl` | `string` | `''` | a public https URL to the image - required, Teams fetches it from there |
| `$filename` | `string` | `''` | the name the recipient sees; '' uses the stored name |

### downloadMedia

*public* - line 326

Inbound images: fetch with the bot's token — Teams does not serve them anonymously.

```php
function downloadMedia(array $item): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$item` | `array` |  | one entry from `parseInbound()`'s 'media' list (url, content_type, filename) |

### testConnection

*public* - line 350

Checks the bot credentials by getting an access token, and returns the app id.

```php
function testConnection(): string
```

### appId

*private* - line 359

The channel's Microsoft app (bot) id.

```php
function appId(): string
```

### accessToken

*private* - line 366

Azure AD client-credentials token for the Bot Framework scope.

```php
function accessToken(): string
```

### lookupUser

*public* - line 410

Who a Teams user is: ['name' => …, 'email' => …], either may be ''. Bot Framework's "get conversation member" - the bot's own token, no Microsoft Graph permission. Used to file their tickets under the person FreeITSM already knows (messagingResolveRequesterByEmail()). Never throws.

The email comes from the organisation's own Microsoft 365 directory, and
`verifyWebhook()` only lets in people from the channel's own tenant, so it
is an address the organisation issued. `email` first; the sign-in name
(userPrincipalName) only when it is shaped like an address, which in most
tenants it is the same thing.

```php
function lookupUser(string $to, string $memberId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$to` | `string` |  | the reply address "<serviceUrl>\|<conversationId>" of the chat the person is in |
| `$memberId` | `string` |  | the sender's Teams id (the message's sender_id) |

### postActivity

*private* - line 448

Posts an activity to a conversation through its Bot Framework service URL and returns the new activity id.

Throws when the address is incomplete, the host is not allowed, or Teams rejects the
message.

```php
function postActivity(string $to, array $activity): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$to` | `string` |  | the reply address "<serviceUrl>\|<conversationId>" |
| `$activity` | `array` |  | the Bot Framework activity to post, e.g. ['type' => 'message', 'text' => ...] |

### botFrameworkKeys

*private* - line 471

Bot Framework's published signing keys, cached for a day. The key set is public and rotates rarely, so a cache file holds nothing secret.

```php
function botFrameworkKeys(): array
```


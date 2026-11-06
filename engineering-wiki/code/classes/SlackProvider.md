# SlackProvider

> Class in [includes/messaging/SlackProvider.php](/code/files/includes/messaging/SlackProvider.php.md), line 33

## Methods

| Method | What it does |
|---|---|
| [verifyChallenge()](#verifychallenge) | Slack's one-off endpoint check. It arrives as a POSTed JSON body of type url_verification, NOT a GET — so the base class's GET-shaped hook can't serve it. The webhook endpoint calls `verifyUrlChallenge()` instead; this stays null so nothing tries the Meta handshake on a Slack channel. |
| [verifyUrlChallenge()](#verifyurlchallenge) | Slack's url_verification handshake: echo back the challenge. |
| [hasSigningSecret()](#hassigningsecret) | Has this channel been given its signing secret yet? Drives the one-time exemption above — see `verifyUrlChallenge()`. |
| [verifyWebhook()](#verifywebhook) | Slack request signing: HMAC-SHA256 of "v0:{timestamp}:{rawBody}" with the signing secret, compared against the X-Slack-Signature header. |
| [parseInbound()](#parseinbound) | Parse an Events API callback into zero or more normalised messages. |
| [sendMessage()](#sendmessage) | Post a reply. `$to` is the reply address parseInbound produced — "C08ABCDE:1719500000.123456" — so the reply lands in the same thread rather than as a new top-level message in the channel. |
| [testConnection()](#testconnection) | Checks the bot token with auth.test and returns the workspace and bot name. |
| [downloadMedia()](#downloadmedia) | Download a file shared in Slack. url_private needs the bot token as a bearer header; without it Slack silently returns its sign-in HTML page with HTTP 200, which is why the content type is checked rather than trusted. |
| [botUserId()](#botuserid) | The bot's own Slack user id, or '' if the token will not authenticate. Used as a harmless probe subject for the profile-lookup health check — a user that certainly exists and certainly is not somebody's real account. |
| [grantedScopes()](#grantedscopes) | The scopes this token was actually granted, lowercased. |
| [channelMembership()](#channelmembership) | Is the app actually a member of this Slack channel? |
| [lookupUser()](#lookupuser) | Look up a Slack user. Returns ['name' => …, 'email' => …]; either may be ''. Never throws — identity is a nice-to-have and must not stop a ticket being raised. A missing email simply means the app has no users:read.email scope. |
| [splitAddress()](#splitaddress) | Split "C08ABCDE:1719500000.123456" into [channel, threadTs]. |
| [call()](#call) | One Slack Web API call. |
| [explainError()](#explainerror) | Slack's error strings are terse machine tokens. An analyst reading "not_in_channel" has no idea the fix is to invite the app to the channel, so the common ones are translated into the action that fixes them. |
| [httpRequestWithHeaders()](#httprequestwithheaders) | `httpRequest()` drops response headers, but Slack reports the token's granted scopes in X-OAuth-Scopes and that is the only way to warn about a missing one BEFORE it fails in use. Same cURL setup, including `sslApplyCurl()`. |
| [toPlainText()](#toplaintext) | Slack text → plain text for the ticket body. Unwraps the link syntax (<https://x\|label> and <@U123>) which would otherwise show as raw angle brackets, and decodes the three entities Slack escapes. |
| [toMrkdwn()](#tomrkdwn) | Plain text → Slack mrkdwn. Escapes the three characters Slack reserves. |

### verifyChallenge

*public* - line 50

Slack's one-off endpoint check. It arrives as a POSTed JSON body of type url_verification, NOT a GET — so the base class's GET-shaped hook can't serve it. The webhook endpoint calls `verifyUrlChallenge()` instead; this stays null so nothing tries the Meta handshake on a Slack channel.

```php
function verifyChallenge(array $get): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$get` | `array` |  | the request's query parameters (not used - Slack's handshake is a POST) |

### verifyUrlChallenge

*public* - line 81

Slack's url_verification handshake: echo back the challenge.

⚠️ THE SETUP DEADLOCK, and why this is allowed through unsigned.

Slack verifies the request URL the instant the app is created from the
manifest. But the signing secret does not exist until the app exists — so
at that moment FreeITSM cannot possibly hold it. Requiring a valid
signature here therefore makes setup impossible, not merely awkward:

    create the app  →  Slack POSTs the challenge  →  403 (no secret yet)
    →  Slack refuses the URL  →  you never get the secret  →  ↺

Found by actually doing it, not by testing: every unit test passed.

So the handshake is answered without a signature ONLY while the channel
has no signing secret — the setup window, which lasts minutes. Once the
secret is stored the endpoint verifies this like anything else.

What this does NOT open up: a real message. `event_callback` always
requires a valid signature, secret or no secret, so nothing can be
injected into the ticket system through the gap. The worst an outsider can
do is learn that a FreeITSM install answers at a URL they already knew.

```php
function verifyUrlChallenge(string $rawBody): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rawBody` | `string` |  | the raw JSON request body; only a url_verification payload gives a challenge |

### hasSigningSecret

*public* - line 95

Has this channel been given its signing secret yet? Drives the one-time exemption above — see `verifyUrlChallenge()`.

```php
function hasSigningSecret(): bool
```

### verifyWebhook

*public* - line 114

Slack request signing: HMAC-SHA256 of "v0:{timestamp}:{rawBody}" with the signing secret, compared against the X-Slack-Signature header.

The timestamp check is NOT optional decoration. Without it a captured
request stays valid forever, so anyone who once saw a legitimate payload
could replay it and inject tickets indefinitely. Slack documents the
five-minute window for exactly this reason.

```php
function verifyWebhook(string $rawBody, array $headers, array $params, string $url): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rawBody` | `string` |  | the raw request body, exactly as received - it is part of the signed string |
| `$headers` | `array` |  | the request headers, lower-cased keys (x-slack-signature, x-slack-request-timestamp) |
| `$params` | `array` |  | the parsed POST parameters (not used) |
| `$url` | `string` |  | the public URL that was hit (not used) |

### parseInbound

*public* - line 152

Parse an Events API callback into zero or more normalised messages.

Returns [] for everything we do not handle, which is most of it — Slack
sends a great deal of traffic and the endpoint simply skips what it does
not recognise.

⚠️ The bot's OWN messages come back as events. Ingesting them would turn
every analyst reply into a new inbound message on the same ticket — an
endless loop of the ticket talking to itself. Three separate guards below,
because Slack marks bot messages inconsistently across message subtypes.

```php
function parseInbound(string $rawBody, array $params): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rawBody` | `string` |  | the raw JSON Events API body |
| `$params` | `array` |  | the parsed POST parameters (not used) |

### sendMessage

*public* - line 233

Post a reply. `$to` is the reply address parseInbound produced — "C08ABCDE:1719500000.123456" — so the reply lands in the same thread rather than as a new top-level message in the channel.

```php
function sendMessage(string $to, string $body): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$to` | `string` |  | the reply address "<slackChannel>:<threadTs>" from `parseInbound()` |
| `$body` | `string` |  | the reply text; HTML from the editor is flattened to text |

### testConnection

*public* - line 257

Checks the bot token with auth.test and returns the workspace and bot name.

Warns when the token lacks the chat:write scope, since replies would then fail.

```php
function testConnection(): string
```

### downloadMedia

*public* - line 283

Download a file shared in Slack. url_private needs the bot token as a bearer header; without it Slack silently returns its sign-in HTML page with HTTP 200, which is why the content type is checked rather than trusted.

```php
function downloadMedia(array $item): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$item` | `array` |  | one entry from `parseInbound()`'s 'media' list (url, content_type, filename) |

### botUserId

*public* - line 322

The bot's own Slack user id, or '' if the token will not authenticate. Used as a harmless probe subject for the profile-lookup health check — a user that certainly exists and certainly is not somebody's real account.

```php
function botUserId(): string
```

### grantedScopes

*public* - line 344

The scopes this token was actually granted, lowercased.

⚠️ This is the single most useful thing to check, because the way it goes
wrong is invisible: Slack grants an app its scopes at INSTALL time, so an
app created from a manifest holds a token missing most of them until
somebody clicks "Reinstall to Workspace". Nothing breaks — tickets simply
arrive without the sender's name. Comparing granted against required is
the only way to see it before a user reports it.

Slack returns them in the X-OAuth-Scopes response header, so any call will
do; auth.test is the cheapest and needs no permission at all.

```php
function grantedScopes(): array
```

### channelMembership

*public* - line 376

Is the app actually a member of this Slack channel?

⚠️ The single most common setup failure, and it looks like nothing at all:
an app cannot read a channel it was not invited to, so messages simply
never arrive. No error anywhere.

Deliberately asks conversations.history rather than conversations.info,
because history needs `channels:history` — which we already require — while
info needs `channels:read`, which we do not. Adding a scope purely to run a
diagnostic would mean asking every Slack admin to approve a permission the
product does not otherwise use.

```php
function channelMembership(string $slackChannelId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$slackChannelId` | `string` |  | the Slack channel id, e.g. 'C08ABCDE' |

**Returns:** array ['in' => bool, 'error' => string]  error is '' when in.

### lookupUser

*public* - line 393

Look up a Slack user. Returns ['name' => …, 'email' => …]; either may be ''. Never throws — identity is a nice-to-have and must not stop a ticket being raised. A missing email simply means the app has no users:read.email scope.

```php
function lookupUser(string $slackUserId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$slackUserId` | `string` |  | the Slack user id, e.g. 'U012ABC' |

### splitAddress

*private* - line 412

Split "C08ABCDE:1719500000.123456" into [channel, threadTs].

```php
function splitAddress(string $addr): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$addr` | `string` |  | the reply address "<slackChannel>:<threadTs>"; a missing part comes back as '' |

### call

*private* - line 441

One Slack Web API call.

⚠️ Arguments are sent FORM-ENCODED, not as a JSON body, and that is not a
style choice. Slack's write methods (chat.postMessage) accept JSON, but its
read methods do not — users.info given a JSON body parses no arguments at
all and answers `user_not_found` for a user that plainly exists. Nothing
errors; the parameter is simply ignored.

Cost me a live debugging session: auth.test takes no arguments, so it
worked, and every test I had written passed. Form encoding is accepted by
every method, so it is the one that cannot be wrong.

⚠️ Slack also answers HTTP 200 for its own errors and puts the truth in
{"ok": false, "error": "…"} — so a status-code check alone reports a failed
call as a success. The body is what matters.

```php
function call(string $method, array $args = []): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$method` | `string` |  | the Slack Web API method, e.g. 'chat.postMessage' |
| `$args` | `array` | `[]` | the method's arguments, sent form-encoded |

### explainError

*private* - line 478

Slack's error strings are terse machine tokens. An analyst reading "not_in_channel" has no idea the fix is to invite the app to the channel, so the common ones are translated into the action that fixes them.

```php
function explainError(string $err): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$err` | `string` |  | Slack's error token, e.g. 'not_in_channel' |

### httpRequestWithHeaders

*private* - line 501

`httpRequest()` drops response headers, but Slack reports the token's granted scopes in X-OAuth-Scopes and that is the only way to warn about a missing one BEFORE it fails in use. Same cURL setup, including `sslApplyCurl()`.

```php
function httpRequestWithHeaders(string $url, array $opts = []): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$url` | `string` |  | the full URL to call |
| `$opts` | `array` | `[]` | method, headers and body, as for `httpRequest()` |

### toPlainText

*private* - line 540

Slack text → plain text for the ticket body. Unwraps the link syntax (<https://x|label> and <@U123>) which would otherwise show as raw angle brackets, and decodes the three entities Slack escapes.

```php
function toPlainText(string $text): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$text` | `string` |  | message text in Slack's own markup |

### toMrkdwn

*private* - line 558

Plain text → Slack mrkdwn. Escapes the three characters Slack reserves.

```php
function toMrkdwn(string $body): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$body` | `string` |  | the analyst's reply, plain text or editor HTML |


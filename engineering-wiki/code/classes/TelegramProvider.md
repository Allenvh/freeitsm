# TelegramProvider

> Class in [includes/messaging/TelegramProvider.php](/code/files/includes/messaging/TelegramProvider.php.md), line 29

## Methods

| Method | What it does |
|---|---|
| [verifyWebhook()](#verifywebhook) | Checks the secret token Telegram sends in the X-Telegram-Bot-Api-Secret-Token header; no secret configured means refuse. |
| [parseInbound()](#parseinbound) | Turns a Telegram update into inbound messages - a CSAT rating press, or a new or edited message. |
| [extractMedia()](#extractmedia) | Pull the largest/only file out of whichever media field is present. |
| [sendMessage()](#sendmessage) | Sends a text message to a chat and returns Telegram's message id. |
| [parseRatingPress()](#parseratingpress) | A rating press (PR #166): one normalised entry carrying 'csat' for ingest.php. |
| [sendRatingRequest()](#sendratingrequest) | Show the 1–5 buttons under the rating question. |
| [closeRatingRequest()](#closeratingrequest) | Swap the rating buttons for the answer: the question stays, a line saying what they chose goes under it, and the keyboard goes (editMessageText without reply_markup removes it). The toast from `answerCallbackQuery()` fades in seconds; this is the record the customer keeps, and it leaves no buttons that would silently do nothing if tapped again. |
| [answerCallbackQuery()](#answercallbackquery) | Stop the button's loading spinner and show a short toast. Best-effort: a failure here must never undo a rating that has already been saved. |
| [requestContact()](#requestcontact) | Ask the chat to share their phone number, via Telegram's native "Share phone number" button (a reply keyboard with request_contact, not an inline button — that's what makes Telegram hand back a verified contact object rather than free-typed, unverifiable text). |
| [sendTemplate()](#sendtemplate) | Telegram has no provider-hosted, pre-approved template system (there is no 24h window to work around), so a "template" here is just this app's own stored body with {{n}} placeholders rendered and sent as a normal message. |
| [downloadMedia()](#downloadmedia) | Downloads an inbound file: resolves its file id to a path, then fetches the bytes. |
| [sendMedia()](#sendmedia) | Send a local file to a chat. Photos go through sendPhoto (Telegram renders them inline, recompressing — exactly what an analyst expects "attach a picture" to look like in the chat); everything else goes through sendDocument (sent byte-for-byte, no recompression, no dimension limits beyond the flat 50MB bot API cap). |
| [setWebhook()](#setwebhook) | Tell Telegram where to deliver this bot's messages, and the secret it must send back with each one (verifyWebhook() checks it). |
| [testConnection()](#testconnection) | Checks the bot token with getMe and returns the bot's name. |

### verifyWebhook

*public* - line 46

Checks the secret token Telegram sends in the X-Telegram-Bot-Api-Secret-Token header; no secret configured means refuse.

```php
function verifyWebhook(string $rawBody, array $headers, array $params, string $url): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rawBody` | `string` |  | the raw request body (not used) |
| `$headers` | `array` |  | the request headers, lower-cased keys - x-telegram-bot-api-secret-token is checked |
| `$params` | `array` |  | the parsed POST parameters (not used) |
| `$url` | `string` |  | the public URL that was hit (not used) |

### parseInbound

*public* - line 65

Turns a Telegram update into inbound messages - a CSAT rating press, or a new or edited message.

```php
function parseInbound(string $rawBody, array $params): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rawBody` | `string` |  | the raw JSON Telegram update |
| `$params` | `array` |  | the parsed POST parameters (not used) |

### extractMedia

*private* - line 152

Pull the largest/only file out of whichever media field is present.

```php
function extractMedia(array $message): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$message` | `array` |  | a Telegram message object, decoded |

### sendMessage

*public* - line 184

Sends a text message to a chat and returns Telegram's message id.

```php
function sendMessage(string $to, string $body): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$to` | `string` |  | the Telegram chat id; '' throws |
| `$body` | `string` |  | the message text |

### parseRatingPress

*private* - line 218

A rating press (PR #166): one normalised entry carrying 'csat' for ingest.php.

```php
function parseRatingPress(array $cb): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$cb` | `array` |  | the update's callback_query object, decoded; only csat:<id>:<1-5> data is read |

### sendRatingRequest

*public* - line 257

Show the 1–5 buttons under the rating question.

```php
function sendRatingRequest(string $to, string $text, int $responseId): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$to` | `string` |  | the Telegram chat id |
| `$text` | `string` |  | the rating question |
| `$responseId` | `int` |  | the ticket_csat_responses id each button carries back |

### closeRatingRequest

*public* - line 298

Swap the rating buttons for the answer: the question stays, a line saying what they chose goes under it, and the keyboard goes (editMessageText without reply_markup removes it). The toast from `answerCallbackQuery()` fades in seconds; this is the record the customer keeps, and it leaves no buttons that would silently do nothing if tapped again.

Best-effort, like the toast: the rating is already saved.

```php
function closeRatingRequest(
    string $chatId,
    string $messageId,
    string $question,
    string $answerLine
): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$chatId` | `string` |  | the Telegram chat id; '' does nothing |
| `$messageId` | `string` |  | the id of the message holding the buttons; '' does nothing |
| `$question` | `string` |  | the question text to keep |
| `$answerLine` | `string` |  | the line saying what they chose, added under the question |

### answerCallbackQuery

*public* - line 323

Stop the button's loading spinner and show a short toast. Best-effort: a failure here must never undo a rating that has already been saved.

```php
function answerCallbackQuery(string $callbackQueryId, string $text): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$callbackQueryId` | `string` |  | the callback_query id from the button press; '' does nothing |
| `$text` | `string` |  | the short toast shown to the customer |

### requestContact

*public* - line 353

Ask the chat to share their phone number, via Telegram's native "Share phone number" button (a reply keyboard with request_contact, not an inline button — that's what makes Telegram hand back a verified contact object rather than free-typed, unverifiable text).

One-time keyboard: it disappears from their client after one tap, so it
doesn't linger once the identity gate (ingest.php) has what it needs.

```php
function requestContact(
    string $chatId,
    string $promptText,
    string $buttonText = 'Share phone number'
): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$chatId` | `string` |  | the Telegram chat id |
| `$promptText` | `string` |  | the message asking for their number |
| `$buttonText` | `string` | `'Share phone number'` | the label on the share button |

### sendTemplate

*public* - line 395

Telegram has no provider-hosted, pre-approved template system (there is no 24h window to work around), so a "template" here is just this app's own stored body with {{n}} placeholders rendered and sent as a normal message.

```php
function sendTemplate(string $to, array $template, array $vars): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$to` | `string` |  | the Telegram chat id |
| `$template` | `array` |  | a messaging_templates row - its body, with {{n}} placeholders |
| `$vars` | `array` |  | ordered values for {{1}}, {{2}}, ... |

### downloadMedia

*public* - line 409

Downloads an inbound file: resolves its file id to a path, then fetches the bytes.

```php
function downloadMedia(array $item): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$item` | `array` |  | one entry from `parseInbound()`'s 'media' list (id = Telegram file id, content_type, filename) |

### sendMedia

*public* - line 460

Send a local file to a chat. Photos go through sendPhoto (Telegram renders them inline, recompressing — exactly what an analyst expects "attach a picture" to look like in the chat); everything else goes through sendDocument (sent byte-for-byte, no recompression, no dimension limits beyond the flat 50MB bot API cap).

Uploaded as multipart/form-data through the shared `httpMultipart()`.

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
| `$to` | `string` |  | the Telegram chat id |
| `$filePath` | `string` |  | the file on this server's disk, already validated |
| `$mimeType` | `string` |  | the file's MIME type - image/* (not SVG) goes as a photo, anything else as a document |
| `$caption` | `string` | `''` | text sent with the file, cut to 1,024 characters; '' for none |
| `$publicUrl` | `string` | `''` | not used - Telegram takes an upload |
| `$filename` | `string` | `''` | the name the recipient sees; '' uses the stored name |

### setWebhook

*public* - line 509

Tell Telegram where to deliver this bot's messages, and the secret it must send back with each one (verifyWebhook() checks it).

Added at merge time to replace a copy-paste `curl …/setWebhook` command in
the settings dialog. That command put the bot token in the admin's browser
and shell history, and asked an IT manager to run a terminal command. Here
the token never leaves the server.

drop_pending_updates is deliberately NOT set: messages sent while the bot
was unregistered should still arrive once it is.

```php
function setWebhook(string $url, string $secret): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$url` | `string` |  | this channel's public webhook URL; must be https |
| `$secret` | `string` |  | the secret Telegram sends back on every delivery (the channel's verify_token) |

**Returns:** string Telegram's own description, e.g. "Webhook was set"

### testConnection

*public* - line 550

Checks the bot token with getMe and returns the bot's name.

Also reads the webhook info and warns when the webhook is not connected or does not
receive every update type it needs.

```php
function testConnection(): string
```


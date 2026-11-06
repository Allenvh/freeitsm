# gmailGetAttachments()

Get Gmail attachments for a message. Returns array of [filename, mimeType, data (base64-decoded)].

```php
function gmailGetAttachments(string $accessToken, string $messageId, array $payload): array
```

Defined in [includes/gmail.php](/code/files/includes/gmail.php.md), line 580.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$accessToken` | `string` |  | a valid Google OAuth access token for the mailbox |
| `$messageId` | `string` |  | the Gmail message id the attachments belong to |
| `$payload` | `array` |  | the message's payload from messages.get; only its top-level parts are read |

**Returns:** `array`

## Calls

[sslApplyCurl()](/code/functions/sslApplyCurl.md)

*Nothing calls this function by name.*


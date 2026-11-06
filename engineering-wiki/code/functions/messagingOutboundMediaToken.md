# messagingOutboundMediaToken()

HMAC binding one attachment id to one expiry — see `messagingOutboundMediaUrl()` and media.php.

Keyed with a key DERIVED from the install's encryption key, not the encryption
key itself: one key, one job. The AES key protects every stored secret, and a
signing scheme should never be the thing that has to be reasoned about when
asking what that key is exposed to. The label versions the scheme, so changing
it later invalidates old links rather than silently accepting them.

Throws if there is no encryption key (getEncryptionKey()); callers that face
the internet catch that and answer 404 rather than a fatal.

```php
function messagingOutboundMediaToken(int $attachmentId, int $expires): string
```

Defined in [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), line 209.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$attachmentId` | `int` |  | the attachment id the token is bound to |
| `$expires` | `int` |  | the Unix timestamp the link stops working |

**Returns:** `string`

## Calls

[getEncryptionKey()](/code/functions/getEncryptionKey.md)

## Called from

- [api/messaging/media.php](/code/files/api/messaging/media.php.md)
- [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md) - in [messagingOutboundMediaUrl()](/code/functions/messagingOutboundMediaUrl.md)


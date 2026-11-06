# messagingDecodeCredentials()

Decrypt + JSON-decode a stored credentials blob into an array (never throws).

```php
function messagingDecodeCredentials($stored): array
```

Defined in [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), line 99.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$stored` |  |  | the credentials column as stored (encrypted JSON); null or '' gives an empty array |

**Returns:** `array`

## Calls

[decryptValue()](/code/functions/decryptValue.md)

## Called from

- [api/messaging/get_channels.php](/code/files/api/messaging/get_channels.php.md)
- [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md) - in [loadMessagingChannel()](/code/functions/loadMessagingChannel.md)


# testCredentials()

Validate credentials with the provider (read-only).

```php
function testCredentials(array $channel): array
```

Defined in [api/messaging/test_channel.php](/code/files/api/messaging/test_channel.php.md), line 97.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$channel` | `array` |  | the messaging_channels row as loaded by `loadMessagingChannel()` |

**Returns:** `array`

## Calls

[messagingProvider()](/code/functions/messagingProvider.md)

## Called from

- [api/messaging/test_channel.php](/code/files/api/messaging/test_channel.php.md)


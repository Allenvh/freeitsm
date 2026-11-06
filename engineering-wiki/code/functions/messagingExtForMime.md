# messagingExtForMime()

A sensible file extension for a media MIME type (for naming downloaded media).

```php
function messagingExtForMime(string $mime): string
```

Defined in [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), line 322.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$mime` | `string` |  | the media's MIME type, parameters after ';' ignored |

**Returns:** `string`

## Called from

- [includes/messaging/MetaCloudProvider.php](/code/files/includes/messaging/MetaCloudProvider.php.md) - in `MetaCloudProvider::downloadMedia`
- [includes/messaging/SlackProvider.php](/code/files/includes/messaging/SlackProvider.php.md) - in `SlackProvider::downloadMedia`
- [includes/messaging/TeamsProvider.php](/code/files/includes/messaging/TeamsProvider.php.md) - in `TeamsProvider::downloadMedia`
- [includes/messaging/TelegramProvider.php](/code/files/includes/messaging/TelegramProvider.php.md) - in `TelegramProvider::downloadMedia`
- [includes/messaging/TwilioProvider.php](/code/files/includes/messaging/TwilioProvider.php.md) - in `TwilioProvider::downloadMedia`


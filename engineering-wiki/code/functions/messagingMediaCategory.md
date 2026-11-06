# messagingMediaCategory()

The category Meta's Media upload endpoint wants for a mime type ('image'|'video'|'audio'|'document').

```php
function messagingMediaCategory(string $mime): string
```

Defined in [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), line 221.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$mime` | `string` |  | a MIME type, parameters after ';' ignored |

**Returns:** `string`

## Called from

- [includes/messaging/MetaCloudProvider.php](/code/files/includes/messaging/MetaCloudProvider.php.md) - in `MetaCloudProvider::sendMedia`


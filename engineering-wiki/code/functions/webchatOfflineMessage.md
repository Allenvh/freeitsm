# webchatOfflineMessage()

The message shown to a visitor when the widget is closed (out of office hours). Uses the admin's custom offline message if set, else a sensible default — so a closed widget is never silent, even if no message was configured.

```php
function webchatOfflineMessage(array $widget): string
```

Defined in [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md), line 41.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$widget` | `array` |  | the widget row (webchatLoadByChannel/ByKey); its offline_message is used if set |

**Returns:** `string`

## Called from

- [api/webchat/config.php](/code/files/api/webchat/config.php.md)
- [api/webchat/send.php](/code/files/api/webchat/send.php.md)


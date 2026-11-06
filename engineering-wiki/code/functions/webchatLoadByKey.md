# webchatLoadByKey()

Load a widget row joined to its channel, keyed by public widget key. Null if missing.

```php
function webchatLoadByKey(PDO $conn, string $widgetKey): ?array
```

Defined in [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md), line 108.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$widgetKey` | `string` |  | the public widget key - untrusted |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 112 | `webchatLoadByKey` | read [webchat_widgets](/code/tables/webchat_widgets.md), read [messaging_channels](/code/tables/messaging_channels.md) |  |

## Called from

- [includes/webchat/public.php](/code/files/includes/webchat/public.php.md) - in [webchatPublicResolve()](/code/functions/webchatPublicResolve.md)


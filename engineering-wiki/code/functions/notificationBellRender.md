# notificationBellRender()

Print the bell: its stylesheet, markup and script, and start it.

```php
function notificationBellRender(string $assetBase, array $cfg): void
```

Defined in [includes/notification_bell.php](/code/files/includes/notification_bell.php.md), line 15.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$assetBase` | `string` |  | URL prefix for assets/ from the current page |
| `$cfg` | `array` |  | NotificationBell.init() config: list, markRead, clear, linkPrefix, seenKey |

**Returns:** `void`

## Calls

[t()](/code/functions/t.md)

## Called from

- [includes/waffle-menu.php](/code/files/includes/waffle-menu.php.md) - in [renderNotificationBell()](/code/functions/renderNotificationBell.md)
- [self-service/includes/header.php](/code/files/self-service/includes/header.php.md)


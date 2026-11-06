# renderNotificationBell()

The global notification bell (discussion #55).

Rendered from the waffle menu, which is on every module's header — which is
what makes it module-independent without any module knowing about it.

Polls a COUNT for the badge and only fetches the list when the panel opens:
this runs in every open tab of every analyst, so the idle cost is the cost
that matters, not the cost of opening it.

```php
function renderNotificationBell($path_prefix)
```

Defined in [includes/waffle-menu.php](/code/files/includes/waffle-menu.php.md), line 1242.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$path_prefix` |  |  | relative path from the page to the app root, e.g. '../', for the API and link URLs |

## Calls

[notificationBellRender()](/code/functions/notificationBellRender.md)

## Called from

- [includes/waffle-menu.php](/code/files/includes/waffle-menu.php.md) - in [renderHeaderRight()](/code/functions/renderHeaderRight.md)


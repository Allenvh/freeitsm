# inboxDisplayNormalise()

Coerce anything into a valid config.

Unknown fields are dropped, unknown styles fall back to that field's default,
and a missing field takes its default — so a config saved by a future version
with more fields still renders correctly on an older one, and vice versa.

```php
function inboxDisplayNormalise($raw): array
```

Defined in [includes/inbox_display.php](/code/files/includes/inbox_display.php.md), line 108.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` |  |  | a saved config - a JSON string or an array of field => style; anything else gives the defaults |

**Returns:** `array`

## Calls

[inboxDisplayRegistry()](/code/functions/inboxDisplayRegistry.md)

## Called from

- [api/tickets/save_inbox_display.php](/code/files/api/tickets/save_inbox_display.php.md)
- [includes/inbox_display.php](/code/files/includes/inbox_display.php.md) - in [inboxDisplayInstallDefault()](/code/functions/inboxDisplayInstallDefault.md), [inboxDisplayForAnalyst()](/code/functions/inboxDisplayForAnalyst.md)


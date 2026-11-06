# inboxDisplayRegistry()

The registry: every field that can appear on a row, and the styles it accepts.

⚠️ THIS IS A WHITELIST, NOT DOCUMENTATION. Style values reach the browser as
CSS class-name fragments, so an unvalidated value is markup injection. Nothing
renders unless it appears here — see `inboxDisplayNormalise()`.

Adding a fourth field (department, ticket type, company) is a change to this
array plus a column in the list query. It was kept to three because that is
what was asked for; the shape is deliberately open.

```php
function inboxDisplayRegistry(): array
```

Defined in [includes/inbox_display.php](/code/files/includes/inbox_display.php.md), line 42.

**Returns:** `array`

## Called from

- [includes/inbox_display.php](/code/files/includes/inbox_display.php.md) - in [inboxDisplayDefaults()](/code/functions/inboxDisplayDefaults.md), [inboxDisplayNormalise()](/code/functions/inboxDisplayNormalise.md)
- [tickets/settings/index.php](/code/files/tickets/settings/index.php.md)


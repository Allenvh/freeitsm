# icsEscape()

Escape a text value per RFC 5545 (backslash, newline, comma, semicolon).

```php
function icsEscape($s): string
```

Defined in [includes/ics.php](/code/files/includes/ics.php.md), line 22.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` |  |  | the text value, cast to string; null gives '' |

**Returns:** `string`

## Called from

- [includes/ics.php](/code/files/includes/ics.php.md) - in [icsHeader()](/code/functions/icsHeader.md), [icsEvent()](/code/functions/icsEvent.md)


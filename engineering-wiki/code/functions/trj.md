# trj()

`tr()` as a JavaScript literal, for the inline scripts these pages carry.

```php
function trj(string $key, string $english, array $params = []): string
```

Defined in [includes/i18n_guarded.php](/code/files/includes/i18n_guarded.php.md), line 99.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` | `string` |  | key within the prefix passed to `i18nGuardedInit()` |
| `$english` | `string` |  | the exact text this call replaces |
| `$params` | `array` | `[]` | {placeholder} => value, applied before JSON-encoding |

**Returns:** `string`

## Calls

[tr()](/code/functions/tr.md)

## Called from

- [tickets/csat/survey.php](/code/files/tickets/csat/survey.php.md)


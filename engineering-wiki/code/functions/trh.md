# trh()

`tr()` escaped for HTML - the normal case for anything going into the page.

```php
function trh(string $key, string $english, array $params = []): string
```

Defined in [includes/i18n_guarded.php](/code/files/includes/i18n_guarded.php.md), line 87.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` | `string` |  | key within the prefix passed to `i18nGuardedInit()` |
| `$english` | `string` |  | the exact text this call replaces |
| `$params` | `array` | `[]` | {placeholder} => value, applied before escaping |

**Returns:** `string`

## Calls

[tr()](/code/functions/tr.md)

## Called from

- [lms/test.php](/code/files/lms/test.php.md)
- [tickets/csat/survey.php](/code/files/tickets/csat/survey.php.md)


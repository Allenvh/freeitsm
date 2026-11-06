# tr()

Translate, with the English as the last line of defence.

```php
function tr(string $key, string $english, array $params = []): string
```

Defined in [includes/i18n_guarded.php](/code/files/includes/i18n_guarded.php.md), line 64.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` | `string` |  | key within the prefix passed to `i18nGuardedInit()` |
| `$english` | `string` |  | the exact text this call replaces |
| `$params` | `array` | `[]` | {placeholder} => value, applied to whichever wins |

**Returns:** `string`

## Called from

- [includes/i18n_guarded.php](/code/files/includes/i18n_guarded.php.md) - in [trh()](/code/functions/trh.md), [trj()](/code/functions/trj.md)
- [lms/test.php](/code/files/lms/test.php.md)


# i18nGuardedInit()

Load i18n behind a guard and fix the namespace prefix `tr()` will use.

```php
function i18nGuardedInit(string $prefix): void
```

Defined in [includes/i18n_guarded.php](/code/files/includes/i18n_guarded.php.md), line 38.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$prefix` | `string` |  | e.g. 'auth' or 'tickets.csat.survey' - no trailing dot. |

**Returns:** `void`

## Called from

- [lms/test.php](/code/files/lms/test.php.md)
- [tickets/csat/survey.php](/code/files/tickets/csat/survey.php.md)


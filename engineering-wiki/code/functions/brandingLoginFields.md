# brandingLoginFields()

THE VALIDATION TABLE — the single source of truth for the login designer.

🔑 One table drives the save endpoint, the renderer AND the settings form, so
a control cannot be added to the screen and forgotten in validation. That is
the failure this shape exists to make impossible: an unvalidated field on the
login page is precisely the hole the rest of this file is about.

Types:
  enum   one of `values`
  colour #rrggbb
  int    clamped to min..max
  text   plain text, trimmed, cut to `max` characters — NEVER HTML
  upload a path inside the branding directory that still exists

```php
function brandingLoginFields(string $scope = 'login'): array
```

Defined in [includes/branding.php](/code/files/includes/branding.php.md), line 158.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$scope` | `string` | `'login'` | which screen - login, portal or home; anything else is treated as login |

**Returns:** `array`

## Calls

[brandingScopes()](/code/functions/brandingScopes.md), [brandingScopeValid()](/code/functions/brandingScopeValid.md)

## Called from

- [api/system/save_branding.php](/code/files/api/system/save_branding.php.md)
- [includes/branding.php](/code/files/includes/branding.php.md) - in [brandingLoginDesign()](/code/functions/brandingLoginDesign.md)
- [system/branding/index.php](/code/files/system/branding/index.php.md)


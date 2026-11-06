# brandingLoginKey()

`form_position` → the settings key it is stored under, for this screen.

```php
function brandingLoginKey(string $field, string $scope = 'login'): string
```

Defined in [includes/branding.php](/code/files/includes/branding.php.md), line 239.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$field` | `string` |  | a field name from `brandingLoginFields()`, e.g. 'form_position' |
| `$scope` | `string` | `'login'` | which screen - login, portal or home; anything else is treated as login |

**Returns:** `string`

## Calls

[brandingScopes()](/code/functions/brandingScopes.md), [brandingScopeValid()](/code/functions/brandingScopeValid.md)

## Called from

- [api/system/save_branding.php](/code/files/api/system/save_branding.php.md)
- [includes/branding.php](/code/files/includes/branding.php.md) - in [brandingLoginDesign()](/code/functions/brandingLoginDesign.md)


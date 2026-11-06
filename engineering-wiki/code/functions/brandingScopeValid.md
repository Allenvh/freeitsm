# brandingScopeValid()

Returns the scope when it is one of the branded screens (login, portal, home), otherwise 'login'.

```php
function brandingScopeValid(string $scope): string
```

Defined in [includes/branding.php](/code/files/includes/branding.php.md), line 137.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$scope` | `string` |  | the screen asked for, possibly from the request - login, portal or home |

**Returns:** `string`

## Calls

[brandingScopes()](/code/functions/brandingScopes.md)

## Called from

- [includes/branding.php](/code/files/includes/branding.php.md) - in [brandingLoginFields()](/code/functions/brandingLoginFields.md), [brandingLoginKey()](/code/functions/brandingLoginKey.md), [brandingLoginDesign()](/code/functions/brandingLoginDesign.md)


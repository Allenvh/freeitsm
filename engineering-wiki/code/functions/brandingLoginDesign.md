# brandingLoginDesign()

The whole design, validated. Safe to call on the login page: it swallows any database failure and returns the defaults.

```php
function brandingLoginDesign(?PDO $conn = null, string $scope = 'login'): array
```

Defined in [includes/branding.php](/code/files/includes/branding.php.md), line 292.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `?PDO` | `null` | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$scope` | `string` | `'login'` | which screen - login, portal or home; anything else is treated as login |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 306 | `brandingLoginDesign` | read [system_settings](/code/tables/system_settings.md) |  |

## Calls

[brandingLoginFields()](/code/functions/brandingLoginFields.md), [brandingLoginKey()](/code/functions/brandingLoginKey.md), [brandingLoginValidate()](/code/functions/brandingLoginValidate.md), [brandingScopes()](/code/functions/brandingScopes.md), [brandingScopeValid()](/code/functions/brandingScopeValid.md), [connectToDatabase()](/code/functions/connectToDatabase.md)

## Called from

- [index.php](/code/files/index.php.md)
- [self-service/login.php](/code/files/self-service/login.php.md)
- [system/branding/index.php](/code/files/system/branding/index.php.md)


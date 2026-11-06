# getMfaAuthContext()

Determine which user type is authenticated and return their context. When both analyst and user sessions coexist, `$preferredType` selects which to use. Returns null if no valid session exists.

```php
function getMfaAuthContext($preferredType = null)
```

Defined in [includes/mfa_helpers.php](/code/files/includes/mfa_helpers.php.md), line 15.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$preferredType` |  | `null` | 'analyst' or 'user' to force a specific context |

**Returns:** array|null ['id' => int, 'table' => string, 'type' => 'analyst'|'user']

## Called from

- [api/myaccount/disable_mfa.php](/code/files/api/myaccount/disable_mfa.php.md)
- [api/myaccount/get_mfa_status.php](/code/files/api/myaccount/get_mfa_status.php.md)
- [api/myaccount/setup_mfa.php](/code/files/api/myaccount/setup_mfa.php.md)
- [api/myaccount/verify_mfa.php](/code/files/api/myaccount/verify_mfa.php.md)


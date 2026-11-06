# filesCompanyLogoPathIsSafe()

A company logo lives in system/uploads/branding/companies/ under our own random name.

```php
function filesCompanyLogoPathIsSafe(string $p): bool
```

Defined in [includes/files/storage.php](/code/files/includes/files/storage.php.md), line 208.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$p` | `string` |  | tenants.logo_path, relative to the application root |

**Returns:** `bool`

## Called from

- [includes/files/storage.php](/code/files/includes/files/storage.php.md) - in [filesDesktopLogo()](/code/functions/filesDesktopLogo.md)


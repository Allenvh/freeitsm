# brandingLogoUrl()

The organisation's logo as a web URL, with the bundled one as the fallback.

⚠️ Never throws and never returns empty: a page that cannot reach the database
still has to render. A missing logo is cosmetic; a fatal on the login page is
not.

```php
function brandingLogoUrl(?PDO $conn = null): string
```

Defined in [includes/branding.php](/code/files/includes/branding.php.md), line 69.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `?PDO` | `null` | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 79 | `brandingLogoUrl` | read [system_settings](/code/tables/system_settings.md) |  |

## Calls

[brandingPathIsSafe()](/code/functions/brandingPathIsSafe.md), [connectToDatabase()](/code/functions/connectToDatabase.md)

## Called from

- [forms/collection.php](/code/files/forms/collection.php.md)
- [forms/edit/index.php](/code/files/forms/edit/index.php.md)
- [forms/fill.php](/code/files/forms/fill.php.md)
- [forms/settings/index.php](/code/files/forms/settings/index.php.md)
- [forms/submissions.php](/code/files/forms/submissions.php.md)
- [includes/files/storage.php](/code/files/includes/files/storage.php.md) - in [filesDesktopLogo()](/code/functions/filesDesktopLogo.md)
- [index.php](/code/files/index.php.md)
- [morning-checks/index.php](/code/files/morning-checks/index.php.md)
- [reporting/packs/designer.php](/code/files/reporting/packs/designer.php.md)
- [self-service/forgot-password.php](/code/files/self-service/forgot-password.php.md)
- [self-service/includes/header.php](/code/files/self-service/includes/header.php.md)
- [self-service/login.php](/code/files/self-service/login.php.md)
- [self-service/register.php](/code/files/self-service/register.php.md)
- [self-service/reset-password.php](/code/files/self-service/reset-password.php.md)
- [system/branding/index.php](/code/files/system/branding/index.php.md)


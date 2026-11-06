# filesDesktopLogo()

The logo for the desktop corner, per System -> Branding: files_logo_mode 'company' (default) | 'none' files_logo_position 'top-right' (default) | 'bottom-right' 'company' = the analyst's active company's logo, else the organisation logo.

```php
function filesDesktopLogo(PDO $conn, int $analystId): array
```

Defined in [includes/files/storage.php](/code/files/includes/files/storage.php.md), line 176.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 187 | `filesDesktopLogo` | read [tenants](/code/tables/tenants.md) |  |

## Calls

[brandingLogoUrl()](/code/functions/brandingLogoUrl.md), [filesCompanyLogoPathIsSafe()](/code/functions/filesCompanyLogoPathIsSafe.md), [filesSetting()](/code/functions/filesSetting.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md)

## Called from

- [files/index.php](/code/files/files/index.php.md)


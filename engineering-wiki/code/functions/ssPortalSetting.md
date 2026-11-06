# ssPortalSetting()

One setting, falling back to its default.

```php
function ssPortalSetting(PDO $conn, string $key): string
```

Defined in [includes/service_status_portal.php](/code/files/includes/service_status_portal.php.md), line 68.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$key` | `string` |  | one of the SS_PORTAL_DEFAULTS keys, e.g. 'service_status_portal_mode' |

**Returns:** `string`

## Calls

[ssPortalSettings()](/code/functions/ssPortalSettings.md)

## Called from

- [includes/service_status_portal.php](/code/files/includes/service_status_portal.php.md) - in [ssPortalUpdatesEnabled()](/code/functions/ssPortalUpdatesEnabled.md)


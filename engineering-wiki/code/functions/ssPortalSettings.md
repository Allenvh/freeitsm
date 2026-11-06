# ssPortalSettings()

All three settings, with defaults filled in for anything unset.

⚠️ NOT CACHED, deliberately. The first version held them in a `static`, which
is per PROCESS rather than per connection or per request — so once it had
read "off", nothing could change that answer for the rest of the request. The
settings page would have saved a change and then re-rendered the old value,
and the tests could not exercise more than one configuration.

It is one small query against three keys. That is not worth a stale read.

```php
function ssPortalSettings(PDO $conn): array
```

Defined in [includes/service_status_portal.php](/code/files/includes/service_status_portal.php.md), line 43.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 49 | `ssPortalSettings` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [api/system/service_status_portal.php](/code/files/api/system/service_status_portal.php.md)
- [includes/service_status_portal.php](/code/files/includes/service_status_portal.php.md) - in [ssPortalSetting()](/code/functions/ssPortalSetting.md), [ssPortalIncidents()](/code/functions/ssPortalIncidents.md)


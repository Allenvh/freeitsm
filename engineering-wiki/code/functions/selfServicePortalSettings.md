# selfServicePortalSettings()

Every self-service portal setting, with its default.

}

```php
function selfServicePortalSettings(PDO $conn): array
```

Defined in [includes/self_service_settings.php](/code/files/includes/self_service_settings.php.md), line 37.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** array{ logo_path:string, header_colour:string, table_header_colour:string, background_pattern:string, allow_self_close:bool, show_my_assets:bool

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 55 | `selfServicePortalSettings` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [api/self-service/close_ticket.php](/code/files/api/self-service/close_ticket.php.md)
- [api/system/self_service_portal.php](/code/files/api/system/self_service_portal.php.md)
- [includes/self_service_settings.php](/code/files/includes/self_service_settings.php.md) - in [selfServicePortalLogoUrl()](/code/functions/selfServicePortalLogoUrl.md)
- [self-service/includes/header.php](/code/files/self-service/includes/header.php.md)
- [self-service/my-equipment.php](/code/files/self-service/my-equipment.php.md)


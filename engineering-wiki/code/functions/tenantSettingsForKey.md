# tenantSettingsForKey()

Every company's answer for one key, for rendering a settings table.

```php
function tenantSettingsForKey(PDO $conn, string $key): array
```

Defined in [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md), line 142.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$key` | `string` |  | the setting key |

**Returns:** array<int,string> tenant_id => value, containing ONLY companies that have their own answer. Anything absent follows the install default, which the caller shows separately.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 146 | `tenantSettingsForKey` | read [tenant_settings](/code/tables/tenant_settings.md) |  |

## Called from

- [api/tickets/get_ticket_classification.php](/code/files/api/tickets/get_ticket_classification.php.md)
- [api/tickets/get_time_tracking_settings.php](/code/files/api/tickets/get_time_tracking_settings.php.md)


# timeTrackingUiOn()

Should the time-recording UI appear for a ticket belonging to this company?

```php
function timeTrackingUiOn(PDO $conn, ?int $tenantId): bool
```

Defined in [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md), line 165.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Calls

[tenantSettingOn()](/code/functions/tenantSettingOn.md)

## Called from

- [api/tickets/get_time_entries.php](/code/files/api/tickets/get_time_entries.php.md)
- [api/tickets/save_time_entry.php](/code/files/api/tickets/save_time_entry.php.md)


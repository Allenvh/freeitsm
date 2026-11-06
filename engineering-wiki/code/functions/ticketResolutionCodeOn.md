# ticketResolutionCodeOn()

Should the resolution code field show when closing a ticket?

```php
function ticketResolutionCodeOn(PDO $conn, ?int $tenantId): bool
```

Defined in [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md), line 259.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Calls

[tenantSettingOn()](/code/functions/tenantSettingOn.md)

## Called from

- [includes/services/mandatory_fields.php](/code/files/includes/services/mandatory_fields.php.md) - in `MandatoryFieldsService::fieldAvailable`
- [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md) - in [ticketClassificationSettings()](/code/functions/ticketClassificationSettings.md)


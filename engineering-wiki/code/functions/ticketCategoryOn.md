# ticketCategoryOn()

Should the "reported as" category field show on a ticket for this company?

```php
function ticketCategoryOn(PDO $conn, ?int $tenantId): bool
```

Defined in [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md), line 247.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Calls

[tenantSettingOn()](/code/functions/tenantSettingOn.md)

## Called from

- [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md)
- [api/self-service/get_ticket_categories.php](/code/files/api/self-service/get_ticket_categories.php.md)
- [includes/services/mandatory_fields.php](/code/files/includes/services/mandatory_fields.php.md) - in `MandatoryFieldsService::fieldAvailable`
- [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md) - in [ticketClassificationSettings()](/code/functions/ticketClassificationSettings.md)


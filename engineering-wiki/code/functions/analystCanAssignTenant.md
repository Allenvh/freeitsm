# analystCanAssignTenant()

May this analyst PIN a channel (or anything else) to this company?

Write-side counterpart to the guard above, for a company id supplied by the
client. NULL is allowed through — it means "shared intake", not a company.
Single-company install → always true (there is only one company to pick).

```php
function analystCanAssignTenant(PDO $conn, int $analystId, $tenantId): bool
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 503.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` |  |  | the company id the client sent; null, '' or 0 means shared / unassigned and is allowed |

**Returns:** `bool`

## Calls

[analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [api/integrations/save_connection.php](/code/files/api/integrations/save_connection.php.md)
- [api/messaging/save_channel.php](/code/files/api/messaging/save_channel.php.md)
- [api/webchat/save_widget.php](/code/files/api/webchat/save_widget.php.md)


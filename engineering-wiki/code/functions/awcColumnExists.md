# awcColumnExists()

True when the table has the column (information_schema), so the sync can wait for Database Verification instead of failing.

```php
function awcColumnExists(PDO $conn, string $table, string $col): bool
```

Defined in [includes/asset_warranty_calendar.php](/code/files/includes/asset_warranty_calendar.php.md), line 312.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$table` | `string` |  | the table name in the current database |
| `$col` | `string` |  | the column name to look for |

**Returns:** `bool`

## Called from

- [includes/asset_warranty_calendar.php](/code/files/includes/asset_warranty_calendar.php.md) - in [syncAssetWarrantyCalendar()](/code/functions/syncAssetWarrantyCalendar.md)
- [includes/domains/calendar.php](/code/files/includes/domains/calendar.php.md) - in [domainSyncExpiryCalendar()](/code/functions/domainSyncExpiryCalendar.md)
- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseSyncCalendar()](/code/functions/leaseSyncCalendar.md)
- [includes/projects/calendar.php](/code/files/includes/projects/calendar.php.md) - in [projectSyncCalendar()](/code/functions/projectSyncCalendar.md)
- [includes/software_licence_calendar.php](/code/files/includes/software_licence_calendar.php.md) - in [syncSoftwareLicenceCalendar()](/code/functions/syncSoftwareLicenceCalendar.md)


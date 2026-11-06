# apiSoftwareLoadApp()

Loads one discovered application row, or answers 404 and stops.

```php
function apiSoftwareLoadApp(PDO $conn, int $appId): array
```

Defined in [api/v1/resources/software.php](/code/files/api/v1/resources/software.php.md), line 123.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$appId` | `int` |  | the software_inventory_apps id |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 124 | `apiSoftwareLoadApp` | read [software_inventory_apps](/code/tables/software_inventory_apps.md) |  |

## Calls

[apiError()](/code/functions/apiError.md)

## Called from

- [api/v1/resources/software.php](/code/files/api/v1/resources/software.php.md) - in [apiSoftwareAppsGet()](/code/functions/apiSoftwareAppsGet.md), [apiSoftwareAppMachinesList()](/code/functions/apiSoftwareAppMachinesList.md)


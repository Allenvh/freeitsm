# apiLoadLicence()

Loads one licence row (apiSoftwareLicenceSelect), or answers 404 and stops.

```php
function apiLoadLicence(PDO $conn, int $licenceId): array
```

Defined in [api/v1/resources/software.php](/code/files/api/v1/resources/software.php.md), line 289.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$licenceId` | `int` |  | the software_licences id |

**Returns:** `array`

## Calls

[apiError()](/code/functions/apiError.md), [apiSoftwareLicenceSelect()](/code/functions/apiSoftwareLicenceSelect.md)

## Called from

- [api/v1/resources/software.php](/code/files/api/v1/resources/software.php.md) - in [apiSoftwareLicencesGet()](/code/functions/apiSoftwareLicencesGet.md), [apiSoftwareLicencesCreate()](/code/functions/apiSoftwareLicencesCreate.md), [apiSoftwareLicencesUpdate()](/code/functions/apiSoftwareLicencesUpdate.md)


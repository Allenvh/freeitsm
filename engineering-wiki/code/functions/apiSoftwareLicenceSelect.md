# apiSoftwareLicenceSelect()

The SELECT for software licences, with the application's name and publisher, the creator's name and how many machines have the application installed.

```php
function apiSoftwareLicenceSelect(): string
```

Defined in [api/v1/resources/software.php](/code/files/api/v1/resources/software.php.md), line 228.

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 229 | `apiSoftwareLicenceSelect` | read [software_inventory_detail](/code/tables/software_inventory_detail.md), read [software_licences](/code/tables/software_licences.md), read [software_inventory_apps](/code/tables/software_inventory_apps.md), read [analysts](/code/tables/analysts.md) |  |

## Called from

- [api/v1/resources/software.php](/code/files/api/v1/resources/software.php.md) - in [apiLoadLicence()](/code/functions/apiLoadLicence.md), [apiSoftwareLicencesList()](/code/functions/apiSoftwareLicencesList.md)


# apiSoftwareAppsList()

GET /software/apps - the discovered applications with install and licence counts, paginated.

?q= matches name or publisher, ?filter= is apps, components or all (the Software
screen's tabs), and ?sort= takes name, publisher, install_count or id, with a leading
- for descending.

```php
function apiSoftwareAppsList(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/software.php](/code/files/api/v1/resources/software.php.md), line 45.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$params` | `array` |  | The ids captured from the URL path, in order, as integers. *([standard](/handbook/standard-parameters.md))* |
| `$body` | `array` |  | The request's JSON body, decoded. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 78 | `apiSoftwareAppsList` | read [software_inventory_apps](/code/tables/software_inventory_apps.md), read [software_inventory_detail](/code/tables/software_inventory_detail.md) |  |
| 88 | `apiSoftwareAppsList` | read [software_licences](/code/tables/software_licences.md), read [software_inventory_apps](/code/tables/software_inventory_apps.md), read [software_inventory_detail](/code/tables/software_inventory_detail.md) |  |

## Calls

[apiError()](/code/functions/apiError.md), [apiIsoDate()](/code/functions/apiIsoDate.md), [apiPagination()](/code/functions/apiPagination.md), [apiRespond()](/code/functions/apiRespond.md)

*Nothing calls this function by name.*


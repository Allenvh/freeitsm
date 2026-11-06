# apiCmdbClassesList()

GET /cmdb/classes - every CMDB class with its icon and how many objects it holds.

Classes are install-wide, but object_count is scoped to the key's companies so it
neither contradicts the object list nor reveals how many objects other companies hold.

```php
function apiCmdbClassesList(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md), line 56.

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
| 62 | `apiCmdbClassesList` | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md), read [cmdb_icons](/code/tables/cmdb_icons.md) |  |

## Calls

[apiKeyTenantFilter()](/code/functions/apiKeyTenantFilter.md), [apiRespond()](/code/functions/apiRespond.md)

*Nothing calls this function by name.*


# apiCmdbLoadObject()

Load one CI; 404 if unknown OR outside the key's companies (multi-tenancy — the key's company scope gates every by-id read/write; a NULL tenant_id normalises to the Default company; no-op at N=1 / all-access key).

```php
function apiCmdbLoadObject(PDO $conn, int $objectId, ?array $apiKey = null): array
```

Defined in [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md), line 152.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$objectId` | `int` |  | the cmdb_objects id |
| `$apiKey` | `?array` | `null` | the calling API key; null skips the company check (every current caller passes one) |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 154 | `apiCmdbLoadObject` | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md), read [tenants](/code/tables/tenants.md) |  |

## Calls

[apiError()](/code/functions/apiError.md), [apiKeyCanAccessTenantRow()](/code/functions/apiKeyCanAccessTenantRow.md)

## Called from

- [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md) - in [apiCmdbObjectsGet()](/code/functions/apiCmdbObjectsGet.md), [apiCmdbObjectsCreate()](/code/functions/apiCmdbObjectsCreate.md), [apiCmdbObjectsUpdate()](/code/functions/apiCmdbObjectsUpdate.md), [apiCmdbObjectImpact()](/code/functions/apiCmdbObjectImpact.md), [apiCmdbObjectTicketsList()](/code/functions/apiCmdbObjectTicketsList.md), [apiCmdbObjectTicketsLink()](/code/functions/apiCmdbObjectTicketsLink.md), [apiCmdbObjectTicketsUnlink()](/code/functions/apiCmdbObjectTicketsUnlink.md)


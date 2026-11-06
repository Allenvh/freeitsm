# apiKeyCheckCompanies()

For System -> API: refuse a company list the key's analyst cannot reach, so a key is never saved promising access it will not have. Throws Exception.

```php
function apiKeyCheckCompanies(PDO $conn, int $analystId, ?array $companyIds): void
```

Defined in [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md), line 207.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | the analyst the API key will act as - not whoever is signed in |
| `$companyIds` | `?array` |  | the company ids chosen for the key, as integers; null means no limit |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 211 | `apiKeyCheckCompanies` | read [tenants](/code/tables/tenants.md) |  |
| 219 | `apiKeyCheckCompanies` | read [tenants](/code/tables/tenants.md) |  |

## Calls

[getAccessibleTenantIds()](/code/functions/getAccessibleTenantIds.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [api/system/api_keys/create_key.php](/code/files/api/system/api_keys/create_key.php.md)
- [api/system/api_keys/update_key.php](/code/files/api/system/api_keys/update_key.php.md)


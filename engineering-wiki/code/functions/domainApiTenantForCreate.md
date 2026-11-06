# domainApiTenantForCreate()

The company a NEW record belongs to: the one the request names (checked against what this analyst may reach), else the analyst's active company.

```php
function domainApiTenantForCreate(PDO $conn, int $analystId, $requested): int
```

Defined in [includes/domains/api_bootstrap.php](/code/files/includes/domains/api_bootstrap.php.md), line 93.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$requested` |  |  | the company_id from the request body (untrusted); null, '' or 0 means the active company |

**Returns:** `int`

## Calls

[analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [domainApiFail()](/code/functions/domainApiFail.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getTenantById()](/code/functions/getTenantById.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [api/domains/accounts.php](/code/files/api/domains/accounts.php.md)
- [api/domains/bulk_add.php](/code/files/api/domains/bulk_add.php.md)
- [api/domains/save.php](/code/files/api/domains/save.php.md)


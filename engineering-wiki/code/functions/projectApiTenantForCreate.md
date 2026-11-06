# projectApiTenantForCreate()

The company a NEW project belongs to: the one the request names (checked against what this analyst may reach), else the analyst's active company.

```php
function projectApiTenantForCreate(PDO $conn, int $analystId, $requested): int
```

Defined in [includes/projects/api_bootstrap.php](/code/files/includes/projects/api_bootstrap.php.md), line 78.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$requested` |  |  | the company id the request asked for (untrusted); null, '' or 0 means the active company |

**Returns:** `int`

## Calls

[analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getTenantById()](/code/functions/getTenantById.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [projectApiFail()](/code/functions/projectApiFail.md)

## Called from

- [api/projects/save.php](/code/files/api/projects/save.php.md)


# requestedTenantId()

The company a request is FOR: `?for_tenant=` when it names a company this analyst may reach, otherwise the active company.

Lets one screen ask about a company other than the active one without
switching, such as the New asset form's Company picker loading that company's
types and locations. 🔴 An id the analyst cannot reach is IGNORED, not
honoured, so the worst a crafted request gets is the active company's lists.

```php
function requestedTenantId(PDO $conn, int $analystId, string $param = 'for_tenant'): int
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 886.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$param` | `string` | `'for_tenant'` | the query-string parameter that names the company (default 'for_tenant') - untrusted |

**Returns:** `int`

## Calls

[analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [api/assets/get_asset_locations.php](/code/files/api/assets/get_asset_locations.php.md)
- [api/assets/get_asset_status_types.php](/code/files/api/assets/get_asset_status_types.php.md)
- [api/assets/get_asset_types.php](/code/files/api/assets/get_asset_types.php.md)


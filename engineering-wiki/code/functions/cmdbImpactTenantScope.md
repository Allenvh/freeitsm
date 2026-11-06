# cmdbImpactTenantScope()

Resolve the company a walk is allowed to stay inside, or null when the install is single-company and there is nothing to constrain.

```php
function cmdbImpactTenantScope(PDO $conn, int $rootId): ?int
```

Defined in [includes/cmdb_impact.php](/code/files/includes/cmdb_impact.php.md), line 107.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$rootId` | `int` |  | cmdb_objects.id the walk starts from; its company (NULL read as the Default) is the scope |

**Returns:** `?int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 111 | `cmdbImpactTenantScope` | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |

## Calls

[getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [api/cmdb/create_impact_diagram.php](/code/files/api/cmdb/create_impact_diagram.php.md)
- [api/cmdb/get_object_impact.php](/code/files/api/cmdb/get_object_impact.php.md)
- [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md) - in [apiCmdbObjectImpact()](/code/functions/apiCmdbObjectImpact.md)


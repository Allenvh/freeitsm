# cmdbBlastRadius()

Transitive blast radius: everything reachable from `$rootId` along impact-bearing edges. Breadth-first, so the depth recorded against each object is the SHORTEST number of hops from the root — which is what the panel groups by, and what makes "2 hops away" meaningful rather than an artefact of traversal order.

Guards: an object is claimed by the first (shortest) path that reaches it, so
cycles terminate; `$maxNodes` and `$maxDepth` bound pathological estates. When
either bites, 'truncated' comes back true — the caller must say so rather than
present a capped list as the whole answer.

```php
function cmdbBlastRadius(
    PDO $conn,
    int $rootId,
    ?int $tenantScope = null,
    int $maxDepth = 20,
    int $maxNodes = 1000
): array
```

Defined in [includes/cmdb_impact.php](/code/files/includes/cmdb_impact.php.md), line 210.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$rootId` | `int` |  | cmdb_objects.id whose failure is being modelled |
| `$tenantScope` | `?int` | `null` | company the walk stays inside (see `cmdbImpactTenantScope()`); null means no limit |
| `$maxDepth` | `int` | `20` | most hops to follow from the root |
| `$maxNodes` | `int` | `1000` | most objects to collect before stopping and flagging truncated |

**Returns:** array{nodes: array, truncated: bool, max_depth_reached: int}

## Calls

[cmdbHasImpactEdgesConfigured()](/code/functions/cmdbHasImpactEdgesConfigured.md), [cmdbImpactEdgesFrom()](/code/functions/cmdbImpactEdgesFrom.md)

## Called from

- [api/cmdb/create_impact_diagram.php](/code/files/api/cmdb/create_impact_diagram.php.md)
- [api/cmdb/get_object_impact.php](/code/files/api/cmdb/get_object_impact.php.md)
- [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md) - in [apiCmdbObjectImpact()](/code/functions/apiCmdbObjectImpact.md)


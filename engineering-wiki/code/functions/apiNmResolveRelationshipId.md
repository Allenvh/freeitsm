# apiNmResolveRelationshipId()

Resolve cmdb_relationship_id input: null clears, an int is validated, 'auto' finds the existing CMDB relationship between the two objects (either direction; null if none).

```php
function apiNmResolveRelationshipId(PDO $conn, $raw, int $fromNodeId, int $toNodeId): ?int
```

Defined in [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md), line 416.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$raw` |  |  | the caller's cmdb_relationship_id: null or '' clears, 'auto' looks one up, otherwise an id |
| `$fromNodeId` | `int` |  | the connector's from node (network_diagram_nodes id), used by 'auto' |
| `$toNodeId` | `int` |  | the connector's to node (network_diagram_nodes id), used by 'auto' |

**Returns:** `?int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 422 | `apiNmResolveRelationshipId` | read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md), read [network_diagram_nodes](/code/tables/network_diagram_nodes.md) |  |
| 435 | `apiNmResolveRelationshipId` | read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md) |  |

## Calls

[apiError()](/code/functions/apiError.md)

## Called from

- [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) - in [apiNmConnectorsCreate()](/code/functions/apiNmConnectorsCreate.md), [apiNmConnectorsUpdate()](/code/functions/apiNmConnectorsUpdate.md)


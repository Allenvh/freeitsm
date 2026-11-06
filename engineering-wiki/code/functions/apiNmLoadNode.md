# apiNmLoadNode()

Loads one node row that belongs to the given diagram, or answers 404 and stops.

```php
function apiNmLoadNode(PDO $conn, int $diagramId, int $nodeId): array
```

Defined in [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md), line 115.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$diagramId` | `int` |  | the network_diagrams id the node must belong to |
| `$nodeId` | `int` |  | the network_diagram_nodes id |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 116 | `apiNmLoadNode` | read [network_diagram_nodes](/code/tables/network_diagram_nodes.md) |  |

## Calls

[apiError()](/code/functions/apiError.md)

## Called from

- [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) - in [apiNmResolveEndpoint()](/code/functions/apiNmResolveEndpoint.md), [apiNmNodesUpdate()](/code/functions/apiNmNodesUpdate.md), [apiNmNodesDelete()](/code/functions/apiNmNodesDelete.md)


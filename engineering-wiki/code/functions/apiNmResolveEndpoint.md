# apiNmResolveEndpoint()

Resolve a connector endpoint to a node id on this diagram. Accepts {prefix}_node_id or {prefix}_object_id (422 if the object isn't on the diagram, or is on it more than once — use node ids to disambiguate).

```php
function apiNmResolveEndpoint(PDO $conn, int $diagramId, array $body, string $prefix): int
```

Defined in [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md), line 385.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$diagramId` | `int` |  | the network_diagrams id the endpoint must be on |
| `$body` | `array` |  | The request's JSON body, decoded. *([standard](/handbook/standard-parameters.md))* |
| `$prefix` | `string` |  | which end to read - 'from' or 'to' - giving {prefix}_node_id / {prefix}_object_id |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 393 | `apiNmResolveEndpoint` | read [network_diagram_nodes](/code/tables/network_diagram_nodes.md) |  |

## Calls

[apiError()](/code/functions/apiError.md), [apiNmLoadNode()](/code/functions/apiNmLoadNode.md)

## Called from

- [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) - in [apiNmConnectorsCreate()](/code/functions/apiNmConnectorsCreate.md)


# apiNmNodesDelete()

DELETE /network-diagrams/{id}/nodes/{node_id} — the node and its connectors.

```php
function apiNmNodesDelete(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md), line 896.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$params` | `array` |  | The ids captured from the URL path, in order, as integers. *([standard](/handbook/standard-parameters.md))* |
| `$body` | `array` |  | The request's JSON body, decoded. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 903 | `apiNmNodesDelete` | **delete** [network_diagram_connectors](/code/tables/network_diagram_connectors.md) |  |
| 906 | `apiNmNodesDelete` | **delete** [network_diagram_nodes](/code/tables/network_diagram_nodes.md) |  |
| 907 | `apiNmNodesDelete` | **update** [network_diagrams](/code/tables/network_diagrams.md) (`updated_datetime`) |  |

## Calls

[apiNmLoadDiagram()](/code/functions/apiNmLoadDiagram.md), [apiNmLoadNode()](/code/functions/apiNmLoadNode.md), [apiNmRequireLeaf()](/code/functions/apiNmRequireLeaf.md), [apiRespond()](/code/functions/apiRespond.md)

*Nothing calls this function by name.*


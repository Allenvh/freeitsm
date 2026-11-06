# apiNmNodesCreate()

POST /network-diagrams/{id}/nodes — add one node (object) or a batch ({"nodes": [...]}).

```php
function apiNmNodesCreate(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md), line 800.

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
| 820 | `apiNmNodesCreate` | read [network_diagram_nodes](/code/tables/network_diagram_nodes.md) |  |
| 833 | `apiNmNodesCreate` | **insert** [network_diagram_nodes](/code/tables/network_diagram_nodes.md) (`diagram_id`, `cmdb_object_id`, `x`, `y`, `size`, `icon_override`) |  |
| 845 | `apiNmNodesCreate` | **update** [network_diagrams](/code/tables/network_diagrams.md) (`updated_datetime`) |  |

## Calls

[apiError()](/code/functions/apiError.md), [apiNmAutoPlacer()](/code/functions/apiNmAutoPlacer.md), [apiNmLoadDiagram()](/code/functions/apiNmLoadDiagram.md), [apiNmNodeSelect()](/code/functions/apiNmNodeSelect.md), [apiNmRequireLeaf()](/code/functions/apiNmRequireLeaf.md), [apiNmValidateNodeInput()](/code/functions/apiNmValidateNodeInput.md), [apiRespond()](/code/functions/apiRespond.md)

*Nothing calls this function by name.*


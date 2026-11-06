# apiNmNodesUpdate()

PATCH /network-diagrams/{id}/nodes/{node_id} — move / resize / re-icon.

```php
function apiNmNodesUpdate(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md), line 862.

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
| 880 | `apiNmNodesUpdate` | **update** [network_diagram_nodes](/code/tables/network_diagram_nodes.md) (`x`, `y`, `size`, `icon_override`) |  |
| 888 | `apiNmNodesUpdate` | **update** [network_diagrams](/code/tables/network_diagrams.md) (`updated_datetime`) |  |

## Calls

[apiError()](/code/functions/apiError.md), [apiNmLoadDiagram()](/code/functions/apiNmLoadDiagram.md), [apiNmLoadNode()](/code/functions/apiNmLoadNode.md), [apiNmNodeSelect()](/code/functions/apiNmNodeSelect.md), [apiNmRequireLeaf()](/code/functions/apiNmRequireLeaf.md), [apiNmValidateIcon()](/code/functions/apiNmValidateIcon.md), [apiNmValidateSize()](/code/functions/apiNmValidateSize.md), [apiRespond()](/code/functions/apiRespond.md), [apiSerializeNmNode()](/code/functions/apiSerializeNmNode.md)

*Nothing calls this function by name.*


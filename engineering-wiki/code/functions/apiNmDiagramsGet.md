# apiNmDiagramsGet()

GET /network-diagrams/{id} — fully hydrated; ?include_properties=true adds each object's CI properties.

```php
function apiNmDiagramsGet(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md), line 550.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$params` | `array` |  | The ids captured from the URL path, in order, as integers. *([standard](/handbook/standard-parameters.md))* |
| `$body` | `array` |  | The request's JSON body, decoded. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Calls

[apiNmConnectorsForDiagram()](/code/functions/apiNmConnectorsForDiagram.md), [apiNmLoadDiagram()](/code/functions/apiNmLoadDiagram.md), [apiNmNodeSelect()](/code/functions/apiNmNodeSelect.md), [apiNmObjectProperties()](/code/functions/apiNmObjectProperties.md), [apiRespond()](/code/functions/apiRespond.md), [apiSerializeNmDiagram()](/code/functions/apiSerializeNmDiagram.md)

*Nothing calls this function by name.*


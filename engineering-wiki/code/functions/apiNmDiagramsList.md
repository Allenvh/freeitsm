# apiNmDiagramsList()

GET /network-diagrams — current versions by default; ?all_versions=true for everything.

```php
function apiNmDiagramsList(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md), line 501.

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
| 505 | `apiNmDiagramsList` | read [network_diagrams](/code/tables/network_diagrams.md) |  |
| 514 | `apiNmDiagramsList` | read [network_diagram_nodes](/code/tables/network_diagram_nodes.md) |  |
| 529 | `apiNmDiagramsList` | read [network_diagrams](/code/tables/network_diagrams.md) |  |

## Calls

[apiNmDiagramSelect()](/code/functions/apiNmDiagramSelect.md), [apiPagination()](/code/functions/apiPagination.md), [apiParseDate()](/code/functions/apiParseDate.md), [apiRespond()](/code/functions/apiRespond.md), [apiSerializeNmDiagram()](/code/functions/apiSerializeNmDiagram.md)

*Nothing calls this function by name.*


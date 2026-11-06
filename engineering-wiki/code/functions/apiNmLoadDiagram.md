# apiNmLoadDiagram()

Loads one diagram row (apiNmDiagramSelect), or answers 404 and stops.

```php
function apiNmLoadDiagram(PDO $conn, int $diagramId): array
```

Defined in [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md), line 88.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$diagramId` | `int` |  | the network_diagrams id (any version) |

**Returns:** `array`

## Calls

[apiError()](/code/functions/apiError.md), [apiNmDiagramSelect()](/code/functions/apiNmDiagramSelect.md)

## Called from

- [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) - in [apiNmDiagramsGet()](/code/functions/apiNmDiagramsGet.md), [apiNmRespondFull()](/code/functions/apiNmRespondFull.md), [apiNmVersionsList()](/code/functions/apiNmVersionsList.md), [apiNmSuggestionsList()](/code/functions/apiNmSuggestionsList.md), [apiNmNodesCreate()](/code/functions/apiNmNodesCreate.md), [apiNmNodesUpdate()](/code/functions/apiNmNodesUpdate.md), [apiNmNodesDelete()](/code/functions/apiNmNodesDelete.md), [apiNmConnectorsCreate()](/code/functions/apiNmConnectorsCreate.md), [apiNmConnectorsUpdate()](/code/functions/apiNmConnectorsUpdate.md), [apiNmConnectorsDelete()](/code/functions/apiNmConnectorsDelete.md)


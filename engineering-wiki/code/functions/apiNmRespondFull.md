# apiNmRespondFull()

Respond with the full hydrated diagram (shared by create/update/version).

```php
function apiNmRespondFull(PDO $conn, int $diagramId, int $status = 200): void
```

Defined in [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md), line 653.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$diagramId` | `int` |  | the network_diagrams id to return |
| `$status` | `int` | `200` | HTTP status code - 201 after a create, 200 otherwise |

**Returns:** `void`

## Calls

[apiNmConnectorsForDiagram()](/code/functions/apiNmConnectorsForDiagram.md), [apiNmLoadDiagram()](/code/functions/apiNmLoadDiagram.md), [apiNmNodeSelect()](/code/functions/apiNmNodeSelect.md), [apiRespond()](/code/functions/apiRespond.md), [apiSerializeNmDiagram()](/code/functions/apiSerializeNmDiagram.md)

## Called from

- [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) - in [apiNmDiagramsCreate()](/code/functions/apiNmDiagramsCreate.md), [apiNmDiagramsUpdate()](/code/functions/apiNmDiagramsUpdate.md), [apiNmVersionsCreate()](/code/functions/apiNmVersionsCreate.md)


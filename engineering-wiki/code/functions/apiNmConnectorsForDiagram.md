# apiNmConnectorsForDiagram()

Connectors with endpoint objects + the CMDB relationship verb they represent.

```php
function apiNmConnectorsForDiagram(PDO $conn, int $diagramId): array
```

Defined in [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md), line 248.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$diagramId` | `int` |  | the network_diagrams id |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 250 | `apiNmConnectorsForDiagram` | read [network_diagram_connectors](/code/tables/network_diagram_connectors.md), read [network_diagram_nodes](/code/tables/network_diagram_nodes.md), read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md), read [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md) |  |

## Called from

- [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) - in [apiNmConnectorHydrated()](/code/functions/apiNmConnectorHydrated.md), [apiNmDiagramsGet()](/code/functions/apiNmDiagramsGet.md), [apiNmRespondFull()](/code/functions/apiNmRespondFull.md)


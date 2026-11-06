# apiNmDiagramSelect()

The SELECT for diagrams, with the author's name and counts of child versions, nodes and connectors - child_count 0 marks the current version.

```php
function apiNmDiagramSelect(): string
```

Defined in [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md), line 74.

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 75 | `apiNmDiagramSelect` | read [network_diagrams](/code/tables/network_diagrams.md), read [network_diagram_nodes](/code/tables/network_diagram_nodes.md), read [network_diagram_connectors](/code/tables/network_diagram_connectors.md), read [analysts](/code/tables/analysts.md) |  |

## Called from

- [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) - in [apiNmLoadDiagram()](/code/functions/apiNmLoadDiagram.md), [apiNmDiagramsList()](/code/functions/apiNmDiagramsList.md), [apiNmVersionsList()](/code/functions/apiNmVersionsList.md)


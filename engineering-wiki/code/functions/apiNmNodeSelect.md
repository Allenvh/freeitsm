# apiNmNodeSelect()

The SELECT for diagram nodes, joined to their CMDB object, its class and the class icon.

```php
function apiNmNodeSelect(): string
```

Defined in [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md), line 210.

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 211 | `apiNmNodeSelect` | read [network_diagram_nodes](/code/tables/network_diagram_nodes.md), read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md), read [cmdb_icons](/code/tables/cmdb_icons.md) |  |

## Called from

- [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) - in [apiNmDiagramsGet()](/code/functions/apiNmDiagramsGet.md), [apiNmRespondFull()](/code/functions/apiNmRespondFull.md), [apiNmNodesCreate()](/code/functions/apiNmNodesCreate.md), [apiNmNodesUpdate()](/code/functions/apiNmNodesUpdate.md)


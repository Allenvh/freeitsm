# apiNmAutoPlacer()

Auto-placement for nodes sent without coordinates: a fresh column to the right of the current bounding box, stacking downward — legible enough for an agent drop; a human tidies the layout in the editor.

```php
function apiNmAutoPlacer(PDO $conn, int $diagramId): callable
```

Defined in [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md), line 482.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$diagramId` | `int` |  | the network_diagrams id whose current nodes set the starting column |

**Returns:** `callable`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 483 | `apiNmAutoPlacer` | read [network_diagram_nodes](/code/tables/network_diagram_nodes.md) |  |

## Called from

- [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) - in [apiNmNodesCreate()](/code/functions/apiNmNodesCreate.md)


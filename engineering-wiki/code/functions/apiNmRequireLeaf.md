# apiNmRequireLeaf()

Writes are leaf-only — a version with children is frozen history.

```php
function apiNmRequireLeaf(array $diagram): void
```

Defined in [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md), line 103.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$diagram` | `array` |  | the diagram row as `apiNmLoadDiagram()` loads it (needs child_count) |

**Returns:** `void`

## Calls

[apiError()](/code/functions/apiError.md)

## Called from

- [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) - in [apiNmNodesCreate()](/code/functions/apiNmNodesCreate.md), [apiNmNodesUpdate()](/code/functions/apiNmNodesUpdate.md), [apiNmNodesDelete()](/code/functions/apiNmNodesDelete.md), [apiNmConnectorsCreate()](/code/functions/apiNmConnectorsCreate.md), [apiNmConnectorsUpdate()](/code/functions/apiNmConnectorsUpdate.md), [apiNmConnectorsDelete()](/code/functions/apiNmConnectorsDelete.md)


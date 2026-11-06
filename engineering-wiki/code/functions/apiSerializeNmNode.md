# apiSerializeNmNode()

Shapes a node row for the API - position, size (and its pixel size), the icon that actually renders, and its CMDB object with class.

```php
function apiSerializeNmNode(array $n): array
```

Defined in [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md), line 225.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$n` | `array` |  | the node row as `apiNmNodeSelect()` loads it, with object, class and class icon |

**Returns:** `array`

## Called from

- [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) - in [apiNmNodesUpdate()](/code/functions/apiNmNodesUpdate.md)


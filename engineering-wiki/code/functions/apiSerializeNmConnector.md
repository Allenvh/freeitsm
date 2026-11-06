# apiSerializeNmConnector()

Shapes a connector row for the API - both endpoints (node and object), the CMDB relationship and its verbs when bound to one, label and line style.

```php
function apiSerializeNmConnector(array $k): array
```

Defined in [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md), line 273.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$k` | `array` |  | the connector row as `apiNmConnectorsForDiagram()` loads it, with endpoint objects and relationship verbs |

**Returns:** `array`

*Nothing calls this function by name.*


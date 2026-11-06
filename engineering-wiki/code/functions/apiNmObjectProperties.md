# apiNmObjectProperties()

Typed property values for the distinct objects behind a diagram's nodes, keyed by object id.

```php
function apiNmObjectProperties(PDO $conn, array $nodeRows): array
```

Defined in [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md), line 596.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$nodeRows` | `array` |  | node rows as `apiNmNodeSelect()` loads them (needs cmdb_object_id and class_id) |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 605 | `apiNmObjectProperties` | read [cmdb_object_properties](/code/tables/cmdb_object_properties.md), read [cmdb_objects](/code/tables/cmdb_objects.md) |  |

## Calls

[apiCmdbClassDefs()](/code/functions/apiCmdbClassDefs.md), [apiIsoDate()](/code/functions/apiIsoDate.md)

## Called from

- [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) - in [apiNmDiagramsGet()](/code/functions/apiNmDiagramsGet.md)


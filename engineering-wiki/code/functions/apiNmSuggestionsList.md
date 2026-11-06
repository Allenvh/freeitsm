# apiNmSuggestionsList()

GET /network-diagrams/{id}/suggestions — CMDB neighbours of on-diagram objects not yet drawn.

```php
function apiNmSuggestionsList(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md), line 708.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$params` | `array` |  | The ids captured from the URL path, in order, as integers. *([standard](/handbook/standard-parameters.md))* |
| `$body` | `array` |  | The request's JSON body, decoded. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 710 | `apiNmSuggestionsList` | read [network_diagram_nodes](/code/tables/network_diagram_nodes.md) |  |
| 733 | `apiNmSuggestionsList` | read [cmdb_object_relationships](/code/tables/cmdb_object_relationships.md), read [cmdb_relationship_types](/code/tables/cmdb_relationship_types.md), read [cmdb_object_properties](/code/tables/cmdb_object_properties.md), read [cmdb_class_properties](/code/tables/cmdb_class_properties.md) |  |
| 775 | `apiNmSuggestionsList` | read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md), read [cmdb_icons](/code/tables/cmdb_icons.md) |  |

## Calls

[apiError()](/code/functions/apiError.md), [apiNmLoadDiagram()](/code/functions/apiNmLoadDiagram.md), [apiRespond()](/code/functions/apiRespond.md)

*Nothing calls this function by name.*


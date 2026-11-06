# apiNmConnectorsUpdate()

PATCH /network-diagrams/{id}/connectors/{connector_id} — label / style / provenance.

```php
function apiNmConnectorsUpdate(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md), line 959.

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
| 975 | `apiNmConnectorsUpdate` | **update** [network_diagram_connectors](/code/tables/network_diagram_connectors.md) (`cmdb_relationship_id`, `label`, `line_style`) |  |
| 986 | `apiNmConnectorsUpdate` | **update** [network_diagrams](/code/tables/network_diagrams.md) (`updated_datetime`) |  |

## Calls

[apiError()](/code/functions/apiError.md), [apiNmConnectorHydrated()](/code/functions/apiNmConnectorHydrated.md), [apiNmLoadConnector()](/code/functions/apiNmLoadConnector.md), [apiNmLoadDiagram()](/code/functions/apiNmLoadDiagram.md), [apiNmRequireLeaf()](/code/functions/apiNmRequireLeaf.md), [apiNmResolveRelationshipId()](/code/functions/apiNmResolveRelationshipId.md), [apiNmValidateLineStyle()](/code/functions/apiNmValidateLineStyle.md), [apiRespond()](/code/functions/apiRespond.md)

*Nothing calls this function by name.*


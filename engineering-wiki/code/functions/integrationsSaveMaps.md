# integrationsSaveMaps()

Replace every mapping of the given types for a connection.

Whole-types-at-once rather than row-by-row because the screen edits them as a
set: a mapping the admin deleted has to disappear, and diffing individual rows
to work that out is more code and more ways to be wrong.

```php
function integrationsSaveMaps(PDO $conn, int $connectionId, array $maps): void
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 1016.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$connectionId` | `int` |  | the integration_connections id whose mappings are replaced |
| `$maps` | `array` |  | [map_type => [local_key => external_key]] |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1030 | `integrationsSaveMaps` | **delete** [integration_field_maps](/code/tables/integration_field_maps.md) |  |
| 1034 | `integrationsSaveMaps` | **insert** [integration_field_maps](/code/tables/integration_field_maps.md) (`connection_id`, `map_type`, `local_key`, `external_key`) |  |

## Calls

[integrationsMapSchemaReady()](/code/functions/integrationsMapSchemaReady.md)

## Called from

- [api/integrations/save_mapping.php](/code/files/api/integrations/save_mapping.php.md)


# integrationsLoadMaps()

Every mapping for one connection, as [map_type][local_key] => external_key. Empty array when nothing is configured or the table is missing — mapping is always optional, and an unmapped install must keep working exactly as before.

```php
function integrationsLoadMaps(PDO $conn, int $connectionId): array
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 988.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$connectionId` | `int` |  | the integration_connections id |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 993 | `integrationsLoadMaps` | read [integration_field_maps](/code/tables/integration_field_maps.md) |  |

## Calls

[integrationsMapSchemaReady()](/code/functions/integrationsMapSchemaReady.md)

## Called from

- [api/integrations/get_mapping.php](/code/files/api/integrations/get_mapping.php.md)
- [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) - in [integrationsEscalate()](/code/functions/integrationsEscalate.md)


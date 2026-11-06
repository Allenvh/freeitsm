# integrationsMapSchemaReady()

Is the mapping table present? Separate gate again, for the same reason as the comment map: an install that ran Database Verification for an earlier release has the connections but not this, and must degrade to "no mapping configured" rather than throwing inside an escalation.

```php
function integrationsMapSchemaReady(PDO $conn): bool
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 969.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 974 | `integrationsMapSchemaReady` | read [integration_field_maps](/code/tables/integration_field_maps.md) |  |

## Called from

- [api/integrations/get_mapping.php](/code/files/api/integrations/get_mapping.php.md)
- [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) - in [integrationsLoadMaps()](/code/functions/integrationsLoadMaps.md), [integrationsSaveMaps()](/code/functions/integrationsSaveMaps.md)


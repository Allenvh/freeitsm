# integration_field_maps

What our values mean in the tracker's vocabulary (V3 mapping). map_type is project | issue_type | priority | custom.  ⚠️ local_key is a STRING, not an FK — what it points at depends on map_type. Project routing namespaces it ('tenant:5' / 'dept:3' / '*') so one map_type covers both routing dimensions and the fallback.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `connection_id` | `INT NOT NULL` |  |
| `map_type` | `VARCHAR(20) NOT NULL` |  |
| `local_key` | `VARCHAR(100) NOT NULL` |  |
| `external_key` | `VARCHAR(255) NOT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md):1030 | `integrationsSaveMaps` | delete |  |  |
| [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md):1034 | `integrationsSaveMaps` | insert | `connection_id`, `map_type`, `local_key`, `external_key` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) | integrationsMapSchemaReady L974, integrationsLoadMaps L993 |  |


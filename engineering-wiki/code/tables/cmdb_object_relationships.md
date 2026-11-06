# cmdb_object_relationships

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `from_object_id` | `INT NOT NULL` |  |
| `to_object_id` | `INT NOT NULL` |  |
| `relationship_type_id` | `INT NOT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md):311 | `CmdbService::deleteObject` | delete |  |  |
| [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md):369 | `CmdbService::createRelationship` | insert | `from_object_id`, `to_object_id`, `relationship_type_id`, `created_datetime` |  |
| [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md):393 | `CmdbService::deleteRelationship` | delete |  |  |
| [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md):408 | `CmdbService::deleteRelationship` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/cmdb/delete_relationship_type.php](/code/files/api/cmdb/delete_relationship_type.php.md) | L28 |  |
| [api/cmdb/generate_object_summary.php](/code/files/api/cmdb/generate_object_summary.php.md) | L105, L118 |  |
| [api/cmdb/get_object.php](/code/files/api/cmdb/get_object.php.md) | L187, L204 |  |
| [api/network-mapper/get_related_objects.php](/code/files/api/network-mapper/get_related_objects.php.md) | L64, L94 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L3276 |  |
| [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md) | apiCmdbObjectsGet L386, apiCmdbObjectsGet L396 |  |
| [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) | apiNmConnectorsForDiagram L250, apiNmResolveRelationshipId L422, apiNmResolveRelationshipId L435, apiNmSuggestionsList L733 |  |
| [includes/cmdb_audit.php](/code/files/includes/cmdb_audit.php.md) | cmdbAuditDisconnected L179 |  |
| [includes/cmdb_impact.php](/code/files/includes/cmdb_impact.php.md) | cmdbDirectImpact L173, cmdbImpactEdgesFrom L297, cmdbImpactEdgesFrom L313 |  |
| [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md) | CmdbService::deleteObject L304, CmdbService::deleteRelationship L401 |  |
| [includes/services/network_mapper.php](/code/files/includes/services/network_mapper.php.md) | NetworkMapperService::resolveRelationshipId L506, NetworkMapperService::resolveRelationshipId L519 |  |
| [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) | warbotToolImpactOf L620 |  |


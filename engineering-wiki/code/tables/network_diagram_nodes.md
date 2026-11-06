# network_diagram_nodes

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `diagram_id` | `INT NOT NULL` |  |
| `cmdb_object_id` | `INT NOT NULL` |  |
| `x` | `INT NOT NULL DEFAULT 0` |  |
| `y` | `INT NOT NULL DEFAULT 0` |  |
| `size` | `VARCHAR(20) NOT NULL DEFAULT 'medium'` |  |
| `icon_override` | `VARCHAR(100) NULL` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3266 |  | delete |  |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3268 |  | delete |  |  |
| [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md):833 | `apiNmNodesCreate` | insert | `diagram_id`, `cmdb_object_id`, `x`, `y`, `size`, `icon_override` |  |
| [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md):880 | `apiNmNodesUpdate` | update | `x`, `y`, `size`, `icon_override` |  |
| [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md):906 | `apiNmNodesDelete` | delete |  |  |
| [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md):310 | `CmdbService::deleteObject` | delete |  |  |
| [includes/services/network_mapper.php](/code/files/includes/services/network_mapper.php.md):170 | `NetworkMapperService::deleteDiagram` | delete |  |  |
| [includes/services/network_mapper.php](/code/files/includes/services/network_mapper.php.md):224 | `NetworkMapperService::createVersion` | insert | `diagram_id`, `cmdb_object_id`, `x`, `y`, `size`, `icon_override` |  |
| [includes/services/network_mapper.php](/code/files/includes/services/network_mapper.php.md):369 | `NetworkMapperService::replaceContents` | delete |  |  |
| [includes/services/network_mapper.php](/code/files/includes/services/network_mapper.php.md):375 | `NetworkMapperService::replaceContents` | insert | `diagram_id`, `cmdb_object_id`, `x`, `y`, `size`, `icon_override` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/network-mapper/get_diagram.php](/code/files/api/network-mapper/get_diagram.php.md) | L72 |  |
| [api/network-mapper/list_diagrams.php](/code/files/api/network-mapper/list_diagrams.php.md) | L47 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L3273, L3274 |  |
| [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) | apiNmDiagramSelect L75, apiNmLoadNode L116, apiNmNodeSelect L211, apiNmConnectorsForDiagram L250, apiNmResolveEndpoint L393, apiNmResolveRelationshipId L422 ... |  |
| [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md) | CmdbService::deleteObject L307 |  |
| [includes/services/network_mapper.php](/code/files/includes/services/network_mapper.php.md) | NetworkMapperService::createVersion L221, NetworkMapperService::autoPlacer L478, NetworkMapperService::resolveRelationshipId L506 |  |


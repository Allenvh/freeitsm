# network_diagram_connectors

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `diagram_id` | `INT NOT NULL` |  |
| `from_node_id` | `INT NOT NULL` |  |
| `to_node_id` | `INT NOT NULL` |  |
| `cmdb_relationship_id` | `INT NULL` |  |
| `label` | `VARCHAR(255) NULL` |  |
| `line_style` | `VARCHAR(20) NULL DEFAULT 'solid'` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3272 |  | delete |  |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3273 |  | delete |  |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3274 |  | delete |  |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3276 |  | update | `cmdb_relationship_id` |  |
| [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md):903 | `apiNmNodesDelete` | delete |  |  |
| [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md):946 | `apiNmConnectorsCreate` | insert | `diagram_id`, `from_node_id`, `to_node_id`, `cmdb_relationship_id`, `label`, `line_style` |  |
| [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md):975 | `apiNmConnectorsUpdate` | update | `cmdb_relationship_id`, `label`, `line_style` |  |
| [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md):997 | `apiNmConnectorsDelete` | delete |  |  |
| [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md):304 | `CmdbService::deleteObject` | update | `cmdb_relationship_id` |  |
| [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md):307 | `CmdbService::deleteObject` | delete |  |  |
| [includes/services/network_mapper.php](/code/files/includes/services/network_mapper.php.md):169 | `NetworkMapperService::deleteDiagram` | delete |  |  |
| [includes/services/network_mapper.php](/code/files/includes/services/network_mapper.php.md):233 | `NetworkMapperService::createVersion` | insert | `diagram_id`, `from_node_id`, `to_node_id`, `cmdb_relationship_id`, `label`, `line_style` |  |
| [includes/services/network_mapper.php](/code/files/includes/services/network_mapper.php.md):368 | `NetworkMapperService::replaceContents` | delete |  |  |
| [includes/services/network_mapper.php](/code/files/includes/services/network_mapper.php.md):399 | `NetworkMapperService::replaceContents` | insert | `diagram_id`, `from_node_id`, `to_node_id`, `cmdb_relationship_id`, `label`, `line_style` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/network-mapper/get_diagram.php](/code/files/api/network-mapper/get_diagram.php.md) | L94 |  |
| [api/network-mapper/list_diagrams.php](/code/files/api/network-mapper/list_diagrams.php.md) | L48 |  |
| [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) | apiNmDiagramSelect L75, apiNmLoadConnector L132, apiNmConnectorsForDiagram L250, apiNmConnectorsCreate L934 |  |
| [includes/services/network_mapper.php](/code/files/includes/services/network_mapper.php.md) | NetworkMapperService::createVersion L230 |  |


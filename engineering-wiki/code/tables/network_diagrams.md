# network_diagrams

Network Mapper — visual diagrams over the CMDB graph (see freeitsm.sql header).

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `parent_diagram_id` | `INT NULL` |  |
| `title` | `VARCHAR(255) NOT NULL` |  |
| `description` | `TEXT NULL` |  |
| `version_label` | `VARCHAR(50) NULL` |  |
| `created_by_analyst_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `paper_size` | `VARCHAR(20) NULL` | Optional paper-size overlay (NULL = no overlay shown). Surfaces on the editor canvas as a dashed outline so analysts can see what will fit before exporting to PNG/PDF. Persisted per-diagram. |
| `paper_orientation` | `VARCHAR(20) NULL` |  |
| `header_left` | `VARCHAR(200) NULL` | Per-diagram header/footer override slots. NULL = inherit the org-wide default from system_settings (`branding_header_left` etc.); non-NULL = explicit override. Renders only when paper_size is set. |
| `header_center` | `VARCHAR(200) NULL` |  |
| `header_right` | `VARCHAR(200) NULL` |  |
| `footer_left` | `VARCHAR(200) NULL` |  |
| `footer_center` | `VARCHAR(200) NULL` |  |
| `footer_right` | `VARCHAR(200) NULL` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3280 |  | update | `parent_diagram_id` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3282 |  | update | `created_by_analyst_id` |  |
| [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md):845 | `apiNmNodesCreate` | update | `updated_datetime` |  |
| [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md):888 | `apiNmNodesUpdate` | update | `updated_datetime` |  |
| [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md):907 | `apiNmNodesDelete` | update | `updated_datetime` |  |
| [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md):953 | `apiNmConnectorsCreate` | update | `updated_datetime` |  |
| [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md):986 | `apiNmConnectorsUpdate` | update | `updated_datetime` |  |
| [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md):998 | `apiNmConnectorsDelete` | update | `updated_datetime` |  |
| [includes/services/network_mapper.php](/code/files/includes/services/network_mapper.php.md):58 | `NetworkMapperService::createDiagram` | insert | `parent_diagram_id`, `title`, `description`, `version_label`, `created_by_analyst_id`, `paper_size`, `paper_orientation`, `header_left`, `header_center`, `header_right` |  |
| [includes/services/network_mapper.php](/code/files/includes/services/network_mapper.php.md):115 | `NetworkMapperService::saveDiagram` | update | `title`, `description`, `version_label`, `paper_size`, `paper_orientation`, `header_left`, `header_center`, `header_right`, `footer_left`, `footer_center` |  |
| [includes/services/network_mapper.php](/code/files/includes/services/network_mapper.php.md):172 | `NetworkMapperService::deleteDiagram` | delete |  |  |
| [includes/services/network_mapper.php](/code/files/includes/services/network_mapper.php.md):198 | `NetworkMapperService::createVersion` | insert | `parent_diagram_id`, `title`, `description`, `version_label`, `created_by_analyst_id`, `paper_size`, `paper_orientation`, `header_left`, `header_center`, `header_right` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/network-mapper/get_diagram.php](/code/files/api/network-mapper/get_diagram.php.md) | L34 |  |
| [api/network-mapper/list_diagrams.php](/code/files/api/network-mapper/list_diagrams.php.md) | L31, countVersionsInChain L75, countVersionsInChain L90 |  |
| [api/network-mapper/list_versions.php](/code/files/api/network-mapper/list_versions.php.md) | L33, L49, L60 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L3266, L3272, L3280 |  |
| [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) | apiNmDiagramSelect L75, apiNmChainIds L149, apiNmChainIds L160, apiNmDiagramsList L505, apiNmDiagramsList L529 |  |
| [includes/services/network_mapper.php](/code/files/includes/services/network_mapper.php.md) | NetworkMapperService::loadDiagram L263, NetworkMapperService::chainIds L296, NetworkMapperService::chainIds L307 |  |


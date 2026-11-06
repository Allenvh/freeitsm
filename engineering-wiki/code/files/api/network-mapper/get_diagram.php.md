# api/network-mapper/get_diagram.php

> API endpoint - 112 lines

API: Get a single diagram version with all nodes and connectors hydrated.

GET ?id=<diagram_id>

Returns:
  diagram   { id, parent_diagram_id, title, description, version_label,
              author_id, author_name, created_at, updated_at, is_current }
  nodes     [{ id, cmdb_object_id, name, class_id, class_name, class_icon,
               is_planned, x, y, size, icon_override }]
  connectors[{ id, from_node_id, to_node_id, cmdb_relationship_id,
               label, line_style }]

## The endpoint

| | |
|---|---|
| URL | `api/network-mapper/get_diagram.php` |
| Protected by | Module access: network-mapper (requireModuleAccessJson) |
| Reads | `GET id` |
| Called from | [assets/js/network-mapper.js](/code/files/assets/js/network-mapper.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 34 |  | read [network_diagrams](/code/tables/network_diagrams.md), read [analysts](/code/tables/analysts.md) |  |
| 72 |  | read [network_diagram_nodes](/code/tables/network_diagram_nodes.md), read [cmdb_objects](/code/tables/cmdb_objects.md), read [cmdb_classes](/code/tables/cmdb_classes.md), read [cmdb_icons](/code/tables/cmdb_icons.md) |  |
| 94 |  | read [network_diagram_connectors](/code/tables/network_diagram_connectors.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


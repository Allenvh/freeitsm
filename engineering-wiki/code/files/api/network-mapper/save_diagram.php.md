# api/network-mapper/save_diagram.php

> API endpoint - 76 lines

API: Save the editable (current) diagram — metadata, nodes, and connectors. Thin UI adapter over NetworkMapperService. Maps the UI's payload shape onto the service's canonical form: node id/tempId -> `ref`, connector from_node_id/to_node_id -> from_ref/to_ref, flat branding -> nested.

## The endpoint

| | |
|---|---|
| URL | `api/network-mapper/save_diagram.php` |
| Protected by | Module access: network-mapper (requireModuleAccessJson) |
| Called from | [assets/js/network-mapper.js](/code/files/assets/js/network-mapper.js.md) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/network_mapper.php](/code/files/includes/services/network_mapper.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


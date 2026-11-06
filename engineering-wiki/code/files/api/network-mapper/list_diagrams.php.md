# api/network-mapper/list_diagrams.php

> API endpoint - 100 lines

API: List Network Mapper diagrams.

Returns the "current" (= leaf, no children) version of each diagram chain.
A chain is a series of versions linked by parent_diagram_id. The leaf is
the editable version; older versions are historical records.

Each returned row carries:
  - id, title, description, version_label, author name, created_at, updated_at
  - node_count, connector_count for the leaf version
  - version_count for the whole chain

## The endpoint

| | |
|---|---|
| URL | `api/network-mapper/list_diagrams.php` |
| Protected by | Module access: network-mapper (requireModuleAccessJson) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [countVersionsInChain()](/code/functions/countVersionsInChain.md) | 70 | Walk back to the root of the chain, then count members. Capped to avoid pathological loops. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 |  | read [network_diagrams](/code/tables/network_diagrams.md), read [analysts](/code/tables/analysts.md) |  |
| 47 |  | read [network_diagram_nodes](/code/tables/network_diagram_nodes.md) |  |
| 48 |  | read [network_diagram_connectors](/code/tables/network_diagram_connectors.md) |  |
| 75 | `countVersionsInChain` | read [network_diagrams](/code/tables/network_diagrams.md) |  |
| 90 | `countVersionsInChain` | read [network_diagrams](/code/tables/network_diagrams.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


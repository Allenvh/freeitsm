# api/network-mapper/list_versions.php

> API endpoint - 82 lines

API: List all versions in the chain containing the requested diagram.

GET ?id=<diagram_id>

Walks back to the root then forward, returning each version in chain order
(oldest first). Used by the version-switcher dropdown in the editor.

## The endpoint

| | |
|---|---|
| URL | `api/network-mapper/list_versions.php` |
| Protected by | Module access: network-mapper (requireModuleAccessJson) |
| Reads | `GET id` |
| Called from | [assets/js/network-mapper.js](/code/files/assets/js/network-mapper.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 33 |  | read [network_diagrams](/code/tables/network_diagrams.md) |  |
| 49 |  | read [network_diagrams](/code/tables/network_diagrams.md) |  |
| 60 |  | read [network_diagrams](/code/tables/network_diagrams.md), read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


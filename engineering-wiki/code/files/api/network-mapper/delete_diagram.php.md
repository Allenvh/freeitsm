# api/network-mapper/delete_diagram.php

> API endpoint - 28 lines

API: Delete a single diagram version (leaf-only). Thin UI adapter over NetworkMapperService.

## The endpoint

| | |
|---|---|
| URL | `api/network-mapper/delete_diagram.php` |
| Protected by | Module access: network-mapper (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/network_mapper.php](/code/files/includes/services/network_mapper.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


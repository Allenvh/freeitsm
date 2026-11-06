# api/network-mapper/create_version.php

> API endpoint - 29 lines

API: Save the current diagram as a new version (clones nodes + connectors). Thin UI adapter over NetworkMapperService.

## The endpoint

| | |
|---|---|
| URL | `api/network-mapper/create_version.php` |
| Protected by | Module access: network-mapper (requireModuleAccessJson) |
| Called from | [assets/js/network-mapper.js](/code/files/assets/js/network-mapper.js.md) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/network_mapper.php](/code/files/includes/services/network_mapper.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


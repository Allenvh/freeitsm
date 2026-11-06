# api/network-mapper/create_diagram.php

> API endpoint - 28 lines

API: Create a new diagram (the v1 of its chain). Thin UI adapter over NetworkMapperService.

## The endpoint

| | |
|---|---|
| URL | `api/network-mapper/create_diagram.php` |
| Protected by | Module access: network-mapper (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/network_mapper.php](/code/files/includes/services/network_mapper.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


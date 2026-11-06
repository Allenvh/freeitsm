# api/assets/import_resolve.php

> API endpoint - 42 lines

API: mark a parked row in the holding area as dealt with.

The row itself is kept — it is the record of what went wrong and what the
source actually said. Resolving only takes it off the "needs attention" list,
so the count means something.

## The endpoint

| | |
|---|---|
| URL | `api/assets/import_resolve.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_IMPORT (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/services/asset_import.php](/code/files/includes/services/asset_import.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


# api/assets/import_run.php

> API endpoint - 80 lines

API: run an import — preview or live.

🔑 Preview and live are the SAME call with one word different. Anything a
preview cannot tell you is something a live run would surprise you with, so
they must not be two code paths that drift.

The uploaded file is re-read from its stored name rather than re-uploaded, so
committing what you previewed cannot accidentally commit a different file.

## The endpoint

| | |
|---|---|
| URL | `api/assets/import_run.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_IMPORT (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/asset_import.php](/code/files/includes/services/asset_import.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


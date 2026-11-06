# api/assets/import_upload.php

> API endpoint - 71 lines

API: take a CSV, store it safely, and report its columns with a suggested mapping. Step one of the import wizard — nothing is imported here.

🔒 The file goes through `uploadStoreFile()`, the one place the upload rules
live: extension + mime whitelist, a filename WE generate, and an .htaccess
that disables execution in the directory. Never the caller's filename.

## The endpoint

| | |
|---|---|
| URL | `api/assets/import_upload.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_IMPORT (requireCapabilityJson) |
| Reads | `POST delimiter` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/uploads.php](/code/files/includes/uploads.php.md), [includes/services/asset_import.php](/code/files/includes/services/asset_import.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [uploadStoreFile()](/code/functions/uploadStoreFile.md)


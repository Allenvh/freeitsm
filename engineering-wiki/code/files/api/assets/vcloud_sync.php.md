# api/assets/vcloud_sync.php

> API endpoint - 43 lines

API Endpoint: test one vCloud Director server, or sync it now. POST {id, action:'test'|'sync'}

## The endpoint

| | |
|---|---|
| URL | `api/assets/vcloud_sync.php` |
| Protected by | Permission: Cap::ASSETS_VCLOUD (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [vcdLoadConnection()](/code/functions/vcdLoadConnection.md), [vcdSyncConnection()](/code/functions/vcdSyncConnection.md), [vcdTest()](/code/functions/vcdTest.md)


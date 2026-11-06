# api/tickets/save_user.php

> API endpoint - 358 lines

API Endpoint: Save (create or update) an end user

Password is optional. Leaving it blank on create means the user is "passwordless" —
the same state inbound-ticket users start in. They can later claim the account via
the self-service portal's register flow by setting their own password.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_user.php` |
| Protected by | Module access: ? (requireAnyModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 71 |  | read [analysts](/code/tables/analysts.md) |  |
| 78 |  | read [users](/code/tables/users.md) |  |
| 145 |  | read [users](/code/tables/users.md) |  |
| 155 |  | read [cost_centres](/code/tables/cost_centres.md) |  |
| 183 |  | read [users](/code/tables/users.md), read [auth_providers](/code/tables/auth_providers.md) |  |
| 229 |  | read [users](/code/tables/users.md) |  |
| 291 |  | **update** [users](/code/tables/users.md) |  |
| 350 |  | **insert** [users](/code/tables/users.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/users.php](/code/files/includes/users.php.md), [includes/cost_centre_assign.php](/code/files/includes/cost_centre_assign.php.md), [includes/carddav_write.php](/code/files/includes/carddav_write.php.md)

**Calls:** [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [analystCanAccessUser()](/code/functions/analystCanAccessUser.md), [cardDavPushPersonChanges()](/code/functions/cardDavPushPersonChanges.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [costCentreCheck()](/code/functions/costCentreCheck.md), [costCentreReady()](/code/functions/costCentreReady.md), [costCentreRecordTenant()](/code/functions/costCentreRecordTenant.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireAnyModuleAccessJson()](/code/functions/requireAnyModuleAccessJson.md), [resolveTenantForNewUser()](/code/functions/resolveTenantForNewUser.md), [userDirectoryOwnedFields()](/code/functions/userDirectoryOwnedFields.md), [userManagerIsSafe()](/code/functions/userManagerIsSafe.md), [userPersonFieldValue()](/code/functions/userPersonFieldValue.md)


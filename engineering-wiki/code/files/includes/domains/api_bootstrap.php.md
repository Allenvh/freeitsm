# includes/domains/api_bootstrap.php

> PHP - 121 lines

Domains — the start of every api/domains/*.php endpoint.

Signed in, allowed into the module, JSON out, a connection and an actor.
Kept here rather than repeated fifteen times so a guard cannot be forgotten
on the sixteenth. Capabilities (settings tabs, auth codes) are checked by the
endpoint that needs them, AFTER this, with `domainRequireCap()`.

Lives in includes/ so it cannot be requested on its own.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [domainApiBody()](/code/functions/domainApiBody.md) | 33 | The decoded JSON body (never null). |
| [domainApiOk()](/code/functions/domainApiOk.md) | 48 | Sends {success: true} merged with `$data` as JSON and ends the request. |
| [domainApiFail()](/code/functions/domainApiFail.md) | 60 | Sends {success: false, error} as JSON, with an HTTP status when one other than 200 is given, and ends the request. |
| [domainRequireCap()](/code/functions/domainRequireCap.md) | 72 | Stops the request with a JSON refusal unless the signed-in analyst holds the capability. |
| [domainHasCap()](/code/functions/domainHasCap.md) | 82 | True when the analyst holds the capability; false on any error. |
| [domainApiTenantForCreate()](/code/functions/domainApiTenantForCreate.md) | 93 | The company a NEW record belongs to: the one the request names (checked against what this analyst may reach), else the analyst's active company. |
| [domainApiRun()](/code/functions/domainApiRun.md) | 110 | Run a service call and turn any failure into the UI's {success:false} shape. |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/services/domains.php](/code/files/includes/services/domains.php.md)

**Included by:** [api/domains/accounts.php](/code/files/api/domains/accounts.php.md), [api/domains/auth_code.php](/code/files/api/domains/auth_code.php.md), [api/domains/bulk_add.php](/code/files/api/domains/bulk_add.php.md), [api/domains/bulk_update.php](/code/files/api/domains/bulk_update.php.md), [api/domains/dashboard.php](/code/files/api/domains/dashboard.php.md), [api/domains/delete.php](/code/files/api/domains/delete.php.md), [api/domains/get.php](/code/files/api/domains/get.php.md), [api/domains/links.php](/code/files/api/domains/links.php.md), [api/domains/list.php](/code/files/api/domains/list.php.md), [api/domains/lookups.php](/code/files/api/domains/lookups.php.md), [api/domains/people.php](/code/files/api/domains/people.php.md), [api/domains/process.php](/code/files/api/domains/process.php.md), [api/domains/save.php](/code/files/api/domains/save.php.md), [api/domains/settings.php](/code/files/api/domains/settings.php.md), [api/domains/statuses.php](/code/files/api/domains/statuses.php.md), [api/domains/tick.php](/code/files/api/domains/tick.php.md), [api/domains/watch.php](/code/files/api/domains/watch.php.md)

**Calls:** [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [analystHasCapability()](/code/functions/analystHasCapability.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getTenantById()](/code/functions/getTenantById.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


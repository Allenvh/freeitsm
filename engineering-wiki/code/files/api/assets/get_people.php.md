# api/assets/get_people.php

> API endpoint - 54 lines

API: the people directory (directory sync slice 1).

GET ?search=<text>&scope=current|leavers|everyone|holding

The companion to get_users_with_assets.php, which lists only current asset
holders. That is the right answer to "who has equipment" and the wrong one to
"who is there" — and you cannot issue a laptop to somebody the list refuses to
show you.

Thin UI adapter over AssetsService::people(), which applies the tenancy filter
to the PERSON, so this returns only people the analyst is allowed to see.

## The endpoint

| | |
|---|---|
| URL | `api/assets/get_people.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |
| Reads | `GET scope`, `GET search`, `GET source`, `GET include_sources` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/assets.php](/code/files/includes/services/assets.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [activeTenantFilter()](/code/functions/activeTenantFilter.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [userSourcesInScope()](/code/functions/userSourcesInScope.md)


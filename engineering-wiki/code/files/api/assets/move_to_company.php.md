# api/assets/move_to_company.php

> API endpoint - 57 lines

API: Assets — move an asset to another company (2.6.0).

Thin UI adapter over AssetsService::moveToCompany, which holds every rule:
both-ends access, the hostname / asset tag / holder refusals, and clearing a
location, type or status the new company cannot see. Nothing is decided
here. The Assets twin of api/tasks/move_to_company.php.

Plain module access, no settings capability: filing an asset under the right
client is ordinary analyst work, like assigning it to somebody.

## The endpoint

| | |
|---|---|
| URL | `api/assets/move_to_company.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/assets.php](/code/files/includes/services/assets.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [t()](/code/functions/t.md)


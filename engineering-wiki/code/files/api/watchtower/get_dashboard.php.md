# api/watchtower/get_dashboard.php

> API endpoint - 43 lines

API: Watchtower Dashboard — Unified attention summary across all modules GET — Returns attention items from every module in a single response

## The endpoint

| | |
|---|---|
| URL | `api/watchtower/get_dashboard.php` |
| Protected by | Module access: watchtower (requireModuleAccessJson) |
| Reads | `GET scope` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getWatchtowerData()](/code/functions/getWatchtowerData.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wtImpersonalCards()](/code/functions/wtImpersonalCards.md), [wtImpersonalOnMine()](/code/functions/wtImpersonalOnMine.md), [wtScopeFor()](/code/functions/wtScopeFor.md), [wtScopeIsValid()](/code/functions/wtScopeIsValid.md)


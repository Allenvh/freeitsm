# api/watchtower/get_settings.php

> API endpoint - 65 lines

API Endpoint: Watchtower settings — the cards, and what each count includes.

Returns every choosable member alongside the current selection, so the screen
never has to know the names of statuses in advance — the whole point of the
exercise. `customised = false` means "following the built-in default", which
is deliberately different from "customised with nothing selected".

## The endpoint

| | |
|---|---|
| URL | `api/watchtower/get_settings.php` |
| Protected by | Module access: watchtower (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 50 |  | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/watchtower_settings.php](/code/files/includes/watchtower_settings.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wtCardKeys()](/code/functions/wtCardKeys.md), [wtItemMembers()](/code/functions/wtItemMembers.md), [wtSelectableItems()](/code/functions/wtSelectableItems.md), [wtVisibleCards()](/code/functions/wtVisibleCards.md)


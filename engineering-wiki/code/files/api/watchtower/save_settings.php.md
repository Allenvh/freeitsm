# api/watchtower/save_settings.php

> API endpoint - 123 lines

API Endpoint: save Watchtower settings.

Two capabilities, checked separately: hiding a card is layout, while changing
what a count includes changes what a number MEANS — narrow "high priority"
and the figure everyone reads each morning quietly changes definition. So a
payload is accepted in parts: whichever sections the caller is allowed to
change are saved, and the rest are ignored rather than failing the lot.

Ids are validated against the live lookup tables. An id that isn't a real
status of the right kind is dropped rather than stored, so nothing can write
a member that later reads back as a ghost.

## The endpoint

| | |
|---|---|
| URL | `api/watchtower/save_settings.php` |
| Protected by | Module access: watchtower (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 52 |  | **insert** [watchtower_items](/code/tables/watchtower_items.md) (`analyst_id`, `item_key`, `is_visible`, `is_customised`) |  |
| 72 |  | **insert** [watchtower_items](/code/tables/watchtower_items.md) (`analyst_id`, `item_key`, `is_visible`, `is_customised`) |  |
| 78 |  | **delete** [watchtower_item_members](/code/tables/watchtower_item_members.md) |  |
| 96 |  | **insert** [watchtower_item_members](/code/tables/watchtower_item_members.md) (`analyst_id`, `item_key`, `entity_type`, `entity_id`) |  |
| 111 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/watchtower_settings.php](/code/files/includes/watchtower_settings.php.md)

**Calls:** [analystHasCapability()](/code/functions/analystHasCapability.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wtCardKeys()](/code/functions/wtCardKeys.md), [wtIdListSql()](/code/functions/wtIdListSql.md), [wtSelectableItems()](/code/functions/wtSelectableItems.md)


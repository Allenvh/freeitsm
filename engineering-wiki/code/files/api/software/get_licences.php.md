# api/software/get_licences.php

> API endpoint - 62 lines

API Endpoint: Get software licences list Returns all licences with application names and publisher info

## The endpoint

| | |
|---|---|
| URL | `api/software/get_licences.php` |
| Protected by | Module access: software (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 21 |  | read [software_licences](/code/tables/software_licences.md), read [software_inventory_apps](/code/tables/software_inventory_apps.md), read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


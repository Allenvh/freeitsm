# api/software/save_app.php

> API endpoint - 111 lines

API Endpoint: create or update a MANUALLY added application (#1549).

POST { id?, display_name, publisher?, app_url?, notes? }

Cloud platforms — Xero, Canva, Figma, Dropbox — have nothing to install, so
nothing discovers them and until now they could not be recorded at all. That
also put the whole of `software_licences` out of reach for SaaS, because its
`app_id` is NOT NULL: a renewal date, a seat count and a cost had nowhere to
hang. This is the front door for both.

---------------------------------------------------------------------------
ONLY MANUAL ROWS ARE EDITABLE HERE
---------------------------------------------------------------------------
An agent-discovered application is a REPORT of what is installed on somebody's
machine. Editing its name here would make the record disagree with the thing
it describes, and the next inventory run would overwrite the edit anyway — so
the screen would appear to accept a change that silently reverted. Refused
with a reason instead.

## The endpoint

| | |
|---|---|
| URL | `api/software/save_app.php` |
| Protected by | Module access: software (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 70 |  | read [software_inventory_apps](/code/tables/software_inventory_apps.md) |  |
| 85 |  | read [software_inventory_apps](/code/tables/software_inventory_apps.md) |  |
| 94 |  | **update** [software_inventory_apps](/code/tables/software_inventory_apps.md) (`display_name`, `publisher`, `app_url`, `notes`) |  |
| 99 |  | **insert** [software_inventory_apps](/code/tables/software_inventory_apps.md) (`display_name`, `publisher`, `app_url`, `notes`, `source`, `created_by`, `first_detected`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)


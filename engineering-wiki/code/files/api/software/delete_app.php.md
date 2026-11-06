# api/software/delete_app.php

> API endpoint - 73 lines

API Endpoint: delete a MANUALLY added application (#1549).

Three guards, in this order:

  1. Manual only. An agent-discovered row is a report of what is installed
     somewhere; deleting it would just make it reappear on the next inventory
     run, so the button would look broken rather than refused.
  2. No licences. `software_licences.app_id` is NOT NULL with a foreign key,
     so the DB would refuse anyway — this says why, and names the number.
  3. No installs. A manual row the agent has since ADOPTED (see the submit
     endpoint) now has real machines behind it, and deleting it would throw
     away that history. Those installs are the answer to "is anyone using
     this?", which is usually the question being asked when someone reaches
     for delete in the first place.

## The endpoint

| | |
|---|---|
| URL | `api/software/delete_app.php` |
| Protected by | Module access: software (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 |  | read [software_inventory_apps](/code/tables/software_inventory_apps.md) |  |
| 47 |  | read [software_licences](/code/tables/software_licences.md) |  |
| 56 |  | read [software_inventory_detail](/code/tables/software_inventory_detail.md) |  |
| 65 |  | **delete** [software_inventory_apps](/code/tables/software_inventory_apps.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)


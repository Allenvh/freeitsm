# api/workflow/delete_format.php

> API endpoint - 71 lines

API: delete a custom webhook message format. Body: { id }

Refuses when:
  - it's a built-in (locked), or
  - a workflow still uses it — the format key is stored inside each
    workflow's action args, so deleting one out from under a live workflow
    would break it at the next fire, with an error nobody could trace back
    to this screen. Same in-use guard as every other settings list.

## The endpoint

| | |
|---|---|
| URL | `api/workflow/delete_format.php` |
| Protected by | Module access: workflow (requireModuleAccessJson); Permission: Cap::WORKFLOW_FORMATS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 35 |  | read [webhook_message_formats](/code/tables/webhook_message_formats.md) |  |
| 50 |  | read [workflows](/code/tables/workflows.md) |  |
| 66 |  | **delete** [webhook_message_formats](/code/tables/webhook_message_formats.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


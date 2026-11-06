# api/assets/handover_templates.php

> API endpoint - 114 lines

API: handover template CRUD + preview (discussion #56).

GET  ?action=list                     every template
GET  ?action=get&id=<int>             one, with its blocks
GET  ?action=meta                     block catalogue, columns, merge codes
POST {action:'save', …}               create or update
POST {action:'default', id}           make one the default
POST {action:'delete', id}            remove one
POST {action:'preview', blocks, user_id}  render without saving

Preview renders through the SAME renderer as the printable page, so the
designer cannot show something the document will not produce.

## The endpoint

| | |
|---|---|
| URL | `api/assets/handover_templates.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_HANDOVER (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/services/handover_templates.php](/code/files/includes/services/handover_templates.php.md), [includes/services/assets.php](/code/files/includes/services/assets.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


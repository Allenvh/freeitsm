# api/workflow/save_format.php

> API endpoint - 114 lines

API: create or update a custom webhook message format.

Body: { id?, key, label, body_template, url_pattern?, markdown_hint?, is_active? }

Built-ins (is_builtin = 1) are LOCKED — same add-only model as the freemail
domain list. Letting someone edit Slack's body template in place would break
every Slack webhook on the install with no obvious cause; if they want a
variant, they copy it to a new key.

The body template is validated hard, because a broken one wouldn't surface
until a real webhook fired.

## The endpoint

| | |
|---|---|
| URL | `api/workflow/save_format.php` |
| Protected by | Module access: workflow (requireModuleAccessJson); Permission: Cap::WORKFLOW_FORMATS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 80 |  | read [webhook_message_formats](/code/tables/webhook_message_formats.md) |  |
| 91 |  | read [webhook_message_formats](/code/tables/webhook_message_formats.md) |  |
| 97 |  | **update** [webhook_message_formats](/code/tables/webhook_message_formats.md) (`format_key`, `label`, `body_template`, `url_pattern`, `markdown_hint`, `is_active`) |  |
| 103 |  | **insert** [webhook_message_formats](/code/tables/webhook_message_formats.md) (`format_key`, `label`, `body_template`, `url_pattern`, `markdown_hint`, `is_builtin`, `is_active`, `display_order`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


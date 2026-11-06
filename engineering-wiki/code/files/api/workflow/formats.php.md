# api/workflow/formats.php

> API endpoint - 65 lines

API: list the webhook message formats (built-in + admin-added).

Read-only. Used by the settings tab AND by the editor, which needs each
format's url_pattern (to warn when the webhook URL doesn't look like it
belongs to the chosen platform) and markdown_hint (so Discord's **bold** vs
Slack's *bold* is stated where you're typing, not in a help page).

## The endpoint

| | |
|---|---|
| URL | `api/workflow/formats.php` |
| Protected by | Module access: workflow (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 25 |  | read [webhook_message_formats](/code/tables/webhook_message_formats.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


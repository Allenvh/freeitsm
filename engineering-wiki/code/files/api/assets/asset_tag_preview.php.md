# api/assets/asset_tag_preview.php

> API endpoint - 41 lines

API: what the next few asset tags would look like under a proposed format, and whether that format is usable. The asset twin of api/tickets/numbering_preview.php.

POST { format, start } -> { success, problems: [], examples: [] }

🔑 Writes NOTHING - it never touches a counter, so an administrator can try
six formats and the next real tag is still the number they expect.

## The endpoint

| | |
|---|---|
| URL | `api/assets/asset_tag_preview.php` |
| Protected by | Module access: assets (requireModuleAccessJson); Permission: Cap::ASSETS_TAGS (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/capabilities.php](/code/files/includes/capabilities.php.md), [includes/services/asset_tags.php](/code/files/includes/services/asset_tags.php.md)

**Calls:** [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


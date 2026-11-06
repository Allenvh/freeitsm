# api/assets/set_asset_field_set.php

> API endpoint - 43 lines

API: attach or detach a field SET on ONE asset — the pilot case.

"Three of these ten televisions are being trialled as smart TVs": tick the
set on those three, and only those three carry IP address, MAC address and
Netflix enabled. The other seven do not show empty fields — they do not have
the fields.

⚠️ Detaching KEEPS the values. Un-ticking hides the fields; it does not throw
away what somebody recorded, so an accidental click is recoverable.

## The endpoint

| | |
|---|---|
| URL | `api/assets/set_asset_field_set.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


# api/assets/get_asset_custom_fields.php

> API endpoint - 151 lines

API: the custom fields that apply to ONE asset, with its values.

Returns the fields grouped by the set they arrive through, plus `via` so the
page can show a set attached to this one asset as a removable chip — the
answer to "why does this TV have a field the type does not?" before anybody
has to ask.

🔑 A field with no value comes back as value === null, NOT omitted and NOT
defaulted. Absent means NOT SET, and the caller must be able to tell that
apart from "no" or zero.

## The endpoint

| | |
|---|---|
| URL | `api/assets/get_asset_custom_fields.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |
| Reads | `GET asset_id` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [assetFieldOptions()](/code/functions/assetFieldOptions.md) | 34 | Dropdown options for a field, memoised for the life of the request. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 39 | `assetFieldOptions` | read [asset_field_options](/code/tables/asset_field_options.md) |  |
| 64 |  | read [assets](/code/tables/assets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


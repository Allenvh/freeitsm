# api/assets/find_asset.php

> API endpoint - 84 lines

API: find ONE asset by something you can scan or read off it.

GET ?q=<serial | hostname | asset tag> -> { success, asset|null, matches }

Built for the tagging loop (assign-tags.php), where the input comes from a
barcode scanner pointed at the manufacturer's own serial sticker — the label
that is already on nearly every piece of kit. Exact matches only: a fuzzy
result would have somebody tag the wrong laptop, and the whole point of the
exercise is that the number on the box matches the number in the database.

Ordered so the most deliberate identifier wins: asset tag (someone typed it),
then serial (the manufacturer's), then hostname.

## The endpoint

| | |
|---|---|
| URL | `api/assets/find_asset.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |
| Reads | `GET q` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 52 |  | read [assets](/code/tables/assets.md), read [asset_types](/code/tables/asset_types.md), read [asset_status_types](/code/tables/asset_status_types.md), read [asset_locations](/code/tables/asset_locations.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/asset_labels.php](/code/files/includes/asset_labels.php.md)

**Calls:** [activeTenantFilter()](/code/functions/activeTenantFilter.md), [assetLabelsSchemaReady()](/code/functions/assetLabelsSchemaReady.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


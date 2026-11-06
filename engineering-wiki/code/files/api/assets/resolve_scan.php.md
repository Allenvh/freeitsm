# api/assets/resolve_scan.php

> API endpoint - 75 lines

API: resolve a scanned FreeITSM asset label to the asset it names.

GET ?token=<qr_token>  ->  { success, asset|null, matches }

The camera scanner (asset-management/scanner.php) decodes a QR into a URL of
the form `<base>/a/<token>`. Opening that URL is the single-asset journey;
this endpoint is the *continuous* one, where the scanner stays on screen and
only needs to know which asset was just seen.

The response shape deliberately matches find_asset.php, because the scanner
calls whichever fits what it decoded — our own label, or the manufacturer's
serial barcode — and must not care which answered.

SECURITY. Identical rules to scan.php, and for the same reasons:
  - the token is not a password, it is an unguessable name for a row, so a
    login and module access are still required;
  - an unknown token and a token belonging to a company this analyst cannot
    see return the SAME answer, or a label becomes an oracle for "does this
    asset exist somewhere on this install?".

## The endpoint

| | |
|---|---|
| URL | `api/assets/resolve_scan.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |
| Reads | `GET token` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 59 |  | read [assets](/code/tables/assets.md), read [asset_types](/code/tables/asset_types.md), read [asset_status_types](/code/tables/asset_status_types.md), read [asset_locations](/code/tables/asset_locations.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/asset_labels.php](/code/files/includes/asset_labels.php.md)

**Calls:** [analystCanAccessAsset()](/code/functions/analystCanAccessAsset.md), [assetIdForToken()](/code/functions/assetIdForToken.md), [assetLabelsSchemaReady()](/code/functions/assetLabelsSchemaReady.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


# api/assets/save_asset_tag.php

> API endpoint - 62 lines

API: set an asset's human-readable tag — the number printed on its label.

POST { asset_id, asset_tag } -> { success, asset_tag }

WHY THIS ISN'T update_asset_field.php
-------------------------------------
That endpoint is a generic single-field writer over a whitelist, and this
field has a rule none of the others have: the tag must be unique WITHIN THE
COMPANY that owns the asset. Two companies on one install may each run their
own LT0001, so the check needs the asset's tenant — which a generic setter
has no reason to know about.

The check is here, in code, and NOT a unique index: `UNIQUE (tenant_id,
asset_tag)` would not hold for the Default company, because MySQL treats
NULLs as distinct in a unique index — two default assets could both be
LT0001 while the index looked like it was guarding them. The same reasoning
already governs hostname uniqueness in this schema.

## The endpoint

| | |
|---|---|
| URL | `api/assets/save_asset_tag.php` |
| Protected by | Module access: assets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/asset_labels.php](/code/files/includes/asset_labels.php.md), [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/services/asset_tags.php](/code/files/includes/services/asset_tags.php.md)

**Calls:** [analystCanAccessAsset()](/code/functions/analystCanAccessAsset.md), [assetLabelsSchemaReady()](/code/functions/assetLabelsSchemaReady.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)


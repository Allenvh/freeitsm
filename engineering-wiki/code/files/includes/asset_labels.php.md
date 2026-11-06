# includes/asset_labels.php

> PHP - 306 lines

QR asset labels — the tag people read, and the token machines read.

TWO IDENTIFIERS, ON PURPOSE
---------------------------
`asset_tag` is the number printed in human-readable text on the label
("LT0001"). It belongs to the company that owns the asset, so two companies
on one install may each legitimately run their own LT0001 — the same reason
hostname uniqueness is per-company here.

`qr_token` is what the QR code actually encodes, as a URL. It is opaque and
install-wide unique, which is what makes the tag collision above a non-issue:
whatever the label says, the scan resolves to exactly one asset.

WHY NOT ENCODE THE ID
---------------------
`…/a/4711` invites somebody to try 4712. The token isn't a secret — the scan
page requires a login and enforces company scope like every other asset read —
but there is no reason to hand out an enumerable index of the estate to
anyone who photographs one label.

WHY THE URL IS SHORT
--------------------
`/a/<token>` rather than `/asset-management/scan.php?token=<token>`: every
character is another QR module, and these get printed at 15mm square on the
side of a laptop that then spends three years being knocked about. Fewer
characters is a bigger, more forgiving code.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [assetLabelsSchemaReady()](/code/functions/assetLabelsSchemaReady.md) | 50 | Does this database have the label columns yet? Cached per request. |
| [assetEnsureToken()](/code/functions/assetEnsureToken.md) | 69 | The token for an asset, minting one if it has never been labelled. |
| [assetIdForToken()](/code/functions/assetIdForToken.md) | 107 | Resolve a scanned token to an asset id. Returns null for unknown tokens. |
| [assetTagAvailable()](/code/functions/assetTagAvailable.md) | 135 | Is this asset tag free within its company? |
| [assetLabelUrl()](/code/functions/assetLabelUrl.md) | 166 | The URL a label's QR encodes. |
| [assetPublicBaseUrl()](/code/functions/assetPublicBaseUrl.md) | 171 | The install's public base, including any sub-folder - see `assetLabelUrl()`. |
| [assetLabelSheetSpecs()](/code/functions/assetLabelSheetSpecs.md) | 180 | Canonical commercial A4 label sheet stock specifications. |
| [assetLabelQrEcLevel()](/code/functions/assetLabelQrEcLevel.md) | 198 | Determine QR code error-correction level based on logo presence. |
| [assetLabelCustomFields()](/code/functions/assetLabelCustomFields.md) | 206 | Custom asset fields a label can print: key ('cf_' + field_key) => its own label. |
| [assetLabelAvailableFields()](/code/functions/assetLabelAvailableFields.md) | 228 | Every field a label can print, with the name the SETTINGS picker shows - built-in fields in the reader's language, custom fields marked as such. |
| [assetLabelPrintFields()](/code/functions/assetLabelPrintFields.md) | 243 | The short names printed ON the label beside each value - space on a 38mm sticker is tight, so "Serial" rather than "Serial / service tag". |
| [assetLabelSettings()](/code/functions/assetLabelSettings.md) | 258 | The label settings for a company: header, footer, which fields in which order, whether to print their names, and the logo inside the QR. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 54 | `assetLabelsSchemaReady` | read [assets](/code/tables/assets.md) |  |
| 72 | `assetEnsureToken` | read [assets](/code/tables/assets.md) |  |
| 83 | `assetEnsureToken` | **update** [assets](/code/tables/assets.md) (`qr_token`) |  |
| 113 | `assetIdForToken` | read [assets](/code/tables/assets.md) |  |
| 139 | `assetTagAvailable` | read [assets](/code/tables/assets.md) |  |
| 209 | `assetLabelCustomFields` | read [asset_fields](/code/tables/asset_fields.md) |  |

## How it hangs together

**Includes:** [includes/functions.php](/code/files/includes/functions.php.md), [includes/public_url.php](/code/files/includes/public_url.php.md), [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md), [includes/branding.php](/code/files/includes/branding.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md)

**Included by:** [api/assets/find_asset.php](/code/files/api/assets/find_asset.php.md), [api/assets/get_assets.php](/code/files/api/assets/get_assets.php.md), [api/assets/get_asset_label_settings.php](/code/files/api/assets/get_asset_label_settings.php.md), [api/assets/resolve_scan.php](/code/files/api/assets/resolve_scan.php.md), [api/assets/save_asset_label_settings.php](/code/files/api/assets/save_asset_label_settings.php.md), [api/assets/save_asset_tag.php](/code/files/api/assets/save_asset_tag.php.md), [asset-management/assign-tags.php](/code/files/asset-management/assign-tags.php.md), [asset-management/labels.php](/code/files/asset-management/labels.php.md), [asset-management/scan.php](/code/files/asset-management/scan.php.md), [asset-management/scanner.php](/code/files/asset-management/scanner.php.md), [includes/record_preview.php](/code/files/includes/record_preview.php.md), [includes/services/asset_tags.php](/code/files/includes/services/asset_tags.php.md)

**Calls:** [brandingPathIsSafe()](/code/functions/brandingPathIsSafe.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [publicAbsoluteUrl()](/code/functions/publicAbsoluteUrl.md), [publicBaseUrl()](/code/functions/publicBaseUrl.md), [t()](/code/functions/t.md), [tenantSetting()](/code/functions/tenantSetting.md)


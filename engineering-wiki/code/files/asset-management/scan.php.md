# asset-management/scan.php

> PHP - 338 lines

The page a QR asset label opens: /a/<token>

MOBILE-FIRST, NOT MOBILE-ADAPTED. This is the one page in FreeITSM whose
primary device is a phone — you are standing in a store room holding the
laptop. So it is built narrow-first with big touch targets and no reliance on
the desktop asset module being responsive; it is a purpose-built surface, not
the asset editor squeezed down. (Making the rest of Assets mobile-friendly is
a separate, worthwhile job — this deliberately does not wait for it.)

It carries the actions you actually want while walking around — set status,
set location — so a phone user is never bounced into a desktop form. Anything
heavier links out to the full record.

SECURITY. The token is not a password: it is an unguessable name for a row.
This page requires a login and enforces module access + company scope exactly
as any other asset read does, so photographing a label gains you nothing you
could not already see.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 65 |  | read [assets](/code/tables/assets.md), read [asset_types](/code/tables/asset_types.md), read [asset_status_types](/code/tables/asset_status_types.md), read [asset_locations](/code/tables/asset_locations.md) |  |
| 88 |  | read [asset_checkout_log](/code/tables/asset_checkout_log.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/asset_labels.php](/code/files/includes/asset_labels.php.md)

**Calls:** [analystCanAccessAsset()](/code/functions/analystCanAccessAsset.md), [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [assetIdForToken()](/code/functions/assetIdForToken.md), [assetLabelsSchemaReady()](/code/functions/assetLabelsSchemaReady.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [fmt_datetime()](/code/functions/fmt_datetime.md), [pageTitle()](/code/functions/pageTitle.md), [t()](/code/functions/t.md)


# includes/services/asset_tags.php

> PHP - 439 lines

AssetTagsService — the ONE place an asset tag is generated, checked or changed (PR #164, built on Sandy's original).

An asset tag is the human number printed on a label: "AST-00042". It is not
the asset's identity — the opaque QR token is (see includes/asset_labels.php
and the QR labels developer guide) — so a tag may be edited, with history,
and a printed label still scans to the right asset.

─────────────────────────────────────────────────────────────────────────────
THE SHAPE IS TICKET NUMBERING'S, ON PURPOSE

A tag is described by ONE format string with tokens, exactly as a ticket
number is (includes/ticket_numbering.php): `AST-{#####}`, `{COMPANY}-LT-{####}`,
`IT{YY}-{######}`. The renderer IS TicketNumbering::render(), so the tokens,
the "{###} is a minimum width, never a limit" rule and {COMPANY}'s code all
behave identically in both places, and an administrator who has set up one
recognises the other.

The counter is claimed the same way too — one atomic upsert, LAST_INSERT_ID,
no read-then-write — and a counter that has fallen behind the tags already in
use is jumped forward with a doubling stride rather than crawled one number at
a time. Called inside the asset's own transaction, the claim rolls back with a
failed asset, so a failed create never burns a number (Sandy's requirement,
kept).

─────────────────────────────────────────────────────────────────────────────
ONE LOCK FOR EVERY WRITE OF A TAG

Uniqueness is per company and checked in code, because a UNIQUE
(tenant_id, asset_tag) index cannot hold for the Default company (MySQL treats
NULLs as distinct — see `assetTagAvailable()`). A check-then-write needs
serialising, so every path that writes a tag — typed in on create, generated,
or changed later on the asset page — runs inside `withLock()`, a MySQL named
lock per company. Two paths with two different locks would let a typed
AST-00005 and a generated AST-00005 both through.

## Classes

- [AssetTagsService](/code/classes/AssetTagsService.md) (class, line 46)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 191 | `AssetTagsService::nextNumber` | read [asset_tag_counters](/code/tables/asset_tag_counters.md) |  |
| 221 | `AssetTagsService::windCounterTo` | **insert** [asset_tag_counters](/code/tables/asset_tag_counters.md) (`counter_key`, `next_value`) |  |
| 235 | `AssetTagsService::claimNext` | **insert** [asset_tag_counters](/code/tables/asset_tag_counters.md) (`counter_key`, `next_value`) |  |
| 343 | `AssetTagsService::clashMessage` | read [assets](/code/tables/assets.md) |  |
| 413 | `AssetTagsService::assign` | read [assets](/code/tables/assets.md) |  |
| 427 | `AssetTagsService::assign` | **update** [assets](/code/tables/assets.md) (`asset_tag`) |  |
| 431 | `AssetTagsService::assign` | **insert** [asset_history](/code/tables/asset_history.md) (`asset_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md), [includes/asset_labels.php](/code/files/includes/asset_labels.php.md), [includes/ticket_numbering.php](/code/files/includes/ticket_numbering.php.md)

**Included by:** [api/assets/asset_tag_preview.php](/code/files/api/assets/asset_tag_preview.php.md), [api/assets/get_asset_tag_settings.php](/code/files/api/assets/get_asset_tag_settings.php.md), [api/assets/save_asset_tag.php](/code/files/api/assets/save_asset_tag.php.md), [api/assets/save_asset_tag_settings.php](/code/files/api/assets/save_asset_tag_settings.php.md), [includes/services/assets.php](/code/files/includes/services/assets.php.md)

**Calls:** [assetTagAvailable()](/code/functions/assetTagAvailable.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [tenantSetting()](/code/functions/tenantSetting.md), [tenantSettingForget()](/code/functions/tenantSettingForget.md)


# analystCanAccessAsset()

May this analyst access this *asset* (by its owning company)? The Asset Management twin of `analystCanAccessChange()` — same rules (single-company → always true; NULL tenant treated as Default-owned; unknown id → false; part-migrated table → true).

```php
function analystCanAccessAsset(PDO $conn, int $analystId, $assetId): bool
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 551.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$assetId` |  |  | the asset id, any form; cast to int, and 0 or less is false |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 560 | `analystCanAccessAsset` | read [assets](/code/tables/assets.md) |  |

## Calls

[analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [tenancyDegradeAllowed()](/code/functions/tenancyDegradeAllowed.md)

## Called from

- [api/assets/assign_asset_user.php](/code/files/api/assets/assign_asset_user.php.md)
- [api/assets/count_matching_disks.php](/code/files/api/assets/count_matching_disks.php.md)
- [api/assets/get_asset_checkout_log.php](/code/files/api/assets/get_asset_checkout_log.php.md)
- [api/assets/get_asset_devices.php](/code/files/api/assets/get_asset_devices.php.md)
- [api/assets/get_asset_disks.php](/code/files/api/assets/get_asset_disks.php.md)
- [api/assets/get_asset_history.php](/code/files/api/assets/get_asset_history.php.md)
- [api/assets/get_asset_software.php](/code/files/api/assets/get_asset_software.php.md)
- [api/assets/get_asset_tickets.php](/code/files/api/assets/get_asset_tickets.php.md)
- [api/assets/get_asset_users.php](/code/files/api/assets/get_asset_users.php.md)
- [api/assets/leases/for_asset.php](/code/files/api/assets/leases/for_asset.php.md)
- [api/assets/resolve_scan.php](/code/files/api/assets/resolve_scan.php.md)
- [api/assets/save_asset_tag.php](/code/files/api/assets/save_asset_tag.php.md)
- [api/assets/save_disk_hide_rule.php](/code/files/api/assets/save_disk_hide_rule.php.md)
- [api/assets/unassign_asset_user.php](/code/files/api/assets/unassign_asset_user.php.md)
- [api/assets/update_asset_field.php](/code/files/api/assets/update_asset_field.php.md)
- [api/tickets/save_ticket_asset.php](/code/files/api/tickets/save_ticket_asset.php.md)
- [asset-management/scan.php](/code/files/asset-management/scan.php.md)
- [includes/documents.php](/code/files/includes/documents.php.md) - in [documentEntityRegistry()](/code/functions/documentEntityRegistry.md)
- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseAssetsAdd()](/code/functions/leaseAssetsAdd.md)
- [includes/projects/links.php](/code/files/includes/projects/links.php.md) - in [projectLinkTargetOk()](/code/functions/projectLinkTargetOk.md)
- [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) - in [recentTrailLabelsForType()](/code/functions/recentTrailLabelsForType.md)
- [includes/record_preview.php](/code/files/includes/record_preview.php.md) - in [recordPreviewAsset()](/code/functions/recordPreviewAsset.md)


# includes/services/assets.php

> PHP - 1459 lines

AssetsService — the shared write rules for assets: create, per-field update (with audit trail + warranty-calendar sync), and user assignment / removal (with the custody trail).

Shared by the UI endpoints (api/assets/update_asset_field.php,
assign_asset_user.php, unassign_asset_user.php) and the REST API
(api/v1/resources/assets.php). Each adapter distils its caller into an
ActorContext + canonical input; this layer validates + writes and returns the
affected id(s) / a small result array, or throws ServiceError. It never emits
HTTP.

Canonical behaviour = the API resource's, so the API stays byte-identical
while the UI's looser writes converge to it:
  - an unknown asset id is a not_found (the UI used to UPDATE 0 rows yet still
    write a history entry for a ghost);
  - lookup ids / dates are validated (422) rather than written blindly;
  - a no-op field write records NO history row (the UI logged one every time);
  - assignments require the requester to exist (422).

Two UI-only behaviours are preserved as optional input, defaulting to the
API's behaviour so the API bytes don't move:
  - `assignUser()` accepts `previous_user_id` — on a re-assign the UI records the
    outgoing holder as the audit's old_value (the API always logs null);
  - `unassignUser()` accepts `$skipAudit` — the UI suppresses the intermediate
    history row when a re-assign removes the previous holder before adding the
    new one (the assign call then logs the "A -> B" transition).

Assets are install-wide (no tenant_id), so companyScope is not consulted.

## Classes

- [AssetsService](/code/classes/AssetsService.md) (class, line 38)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 133 | `AssetsService::createAsset` | read [assets](/code/tables/assets.md) |  |
| 191 | `AssetsService::createAsset` | **insert** [assets](/code/tables/assets.md) |  |
| 248 | `AssetsService::updateFields` | read [assets](/code/tables/assets.md) |  |
| 286 | `AssetsService::updateFields` | **update** [assets](/code/tables/assets.md) |  |
| 328 | `AssetsService::assignAnalyst` | read [analysts](/code/tables/analysts.md) |  |
| 331 | `AssetsService::assignAnalyst` | read [analysts](/code/tables/analysts.md) |  |
| 348 | `AssetsService::assignAnalyst` | read [users_assets](/code/tables/users_assets.md) |  |
| 357 | `AssetsService::assignAnalyst` | **insert** [users_assets](/code/tables/users_assets.md) (`asset_id`, `analyst_id`, `assigned_by_analyst_id`, `notes`, `expected_return_date`, `assigned_datetime`) |  |
| 366 | `AssetsService::assignAnalyst` | **insert** [asset_checkout_log](/code/tables/asset_checkout_log.md) (`asset_id`, `user_id`, `user_name`, `action`, `expected_return_date`, `analyst_id`, `notes`, `action_datetime`) |  |
| 373 | `AssetsService::assignAnalyst` | read [analysts](/code/tables/analysts.md) |  |
| 405 | `AssetsService::assignUser` | read [users](/code/tables/users.md) |  |
| 408 | `AssetsService::assignUser` | read [users](/code/tables/users.md) |  |
| 423 | `AssetsService::assignUser` | read [users_assets](/code/tables/users_assets.md) |  |
| 430 | `AssetsService::assignUser` | **insert** [users_assets](/code/tables/users_assets.md) (`asset_id`, `user_id`, `assigned_by_analyst_id`, `notes`, `expected_return_date`, `assigned_datetime`) |  |
| 437 | `AssetsService::assignUser` | **insert** [asset_checkout_log](/code/tables/asset_checkout_log.md) (`asset_id`, `user_id`, `user_name`, `action`, `expected_return_date`, `analyst_id`, `notes`, `action_datetime`) |  |
| 445 | `AssetsService::assignUser` | read [users](/code/tables/users.md) |  |
| 480 | `AssetsService::unassignUser` | read [users_assets](/code/tables/users_assets.md), read [users](/code/tables/users.md) |  |
| 490 | `AssetsService::unassignUser` | **delete** [users_assets](/code/tables/users_assets.md) |  |
| 494 | `AssetsService::unassignUser` | **insert** [asset_checkout_log](/code/tables/asset_checkout_log.md) (`asset_id`, `user_id`, `user_name`, `action`, `expected_return_date`, `analyst_id`, `action_datetime`) |  |
| 523 | `AssetsService::unassignAnalyst` | read [users_assets](/code/tables/users_assets.md), read [analysts](/code/tables/analysts.md) |  |
| 533 | `AssetsService::unassignAnalyst` | **delete** [users_assets](/code/tables/users_assets.md) |  |
| 541 | `AssetsService::unassignAnalyst` | **insert** [asset_checkout_log](/code/tables/asset_checkout_log.md) (`asset_id`, `user_id`, `user_name`, `action`, `expected_return_date`, `analyst_id`, `action_datetime`) |  |
| 565 | `AssetsService::dispatch` | read [assets](/code/tables/assets.md) |  |
| 636 | `AssetsService::moveToCompany` | read [assets](/code/tables/assets.md) |  |
| 643 | `AssetsService::moveToCompany` | read [assets](/code/tables/assets.md) |  |
| 652 | `AssetsService::moveToCompany` | read [users_assets](/code/tables/users_assets.md), read [users](/code/tables/users.md) |  |
| 666 | `AssetsService::moveToCompany` | read [lease_assets](/code/tables/lease_assets.md), read [lease_schedules](/code/tables/lease_schedules.md), read [leases](/code/tables/leases.md), read [lease_outcomes](/code/tables/lease_outcomes.md) |  |
| 679 | `AssetsService::moveToCompany` | read [asset_locations](/code/tables/asset_locations.md) |  |
| 699 | `AssetsService::moveToCompany` | **update** [assets](/code/tables/assets.md) |  |
| 722 | `AssetsService::loadRow` | read [assets](/code/tables/assets.md) |  |
| 745 | `AssetsService::auditWrite` | **insert** [asset_history](/code/tables/asset_history.md) (`asset_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |
| 789 | `AssetsService::validateField` | read [suppliers](/code/tables/suppliers.md) |  |
| 860 | `AssetsService::auditDisplay` | read [suppliers](/code/tables/suppliers.md) |  |
| 913 | `AssetsService::usersHoldingAssets` | read [users_assets](/code/tables/users_assets.md), read [users](/code/tables/users.md), read [assets](/code/tables/assets.md) |  |
| 1000 | `AssetsService::people` | read [users_assets](/code/tables/users_assets.md) |  |
| 1008 | `AssetsService::people` | read [users_assets](/code/tables/users_assets.md), read [users](/code/tables/users.md), read [auth_providers](/code/tables/auth_providers.md) |  |
| 1081 | `AssetsService::assetsForUser` | read [users](/code/tables/users.md), read [auth_providers](/code/tables/auth_providers.md) |  |
| 1119 | `AssetsService::assetsForUser` | read [users](/code/tables/users.md) |  |
| 1135 | `AssetsService::assetsForUser` | read [users_assets](/code/tables/users_assets.md), read [assets](/code/tables/assets.md), read [asset_types](/code/tables/asset_types.md), read [asset_status_types](/code/tables/asset_status_types.md), read [asset_locations](/code/tables/asset_locations.md), read [analysts](/code/tables/analysts.md) |  |
| 1175 | `AssetsService::assetsForUser` | read [asset_field_sets](/code/tables/asset_field_sets.md) |  |
| 1238 | `AssetsService::getIgnoredServiceTags` | read [system_settings](/code/tables/system_settings.md) |  |
| 1304 | `AssetsService::resolveAssetIdentity` | read [assets](/code/tables/assets.md) |  |
| 1315 | `AssetsService::resolveAssetIdentity` | read [assets](/code/tables/assets.md) |  |
| 1321 | `AssetsService::resolveAssetIdentity` | read [assets](/code/tables/assets.md) |  |
| 1331 | `AssetsService::resolveAssetIdentity` | read [assets](/code/tables/assets.md) |  |
| 1360 | `AssetsService::updateAssetHostname` | read [assets](/code/tables/assets.md) |  |
| 1367 | `AssetsService::updateAssetHostname` | read [assets](/code/tables/assets.md) |  |
| 1371 | `AssetsService::updateAssetHostname` | **update** [assets](/code/tables/assets.md) (`hostname`) |  |
| 1437 | `AssetsService::createDiscoveredAsset` | **insert** [assets](/code/tables/assets.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/users.php](/code/files/includes/users.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md), [includes/cost_centre_assign.php](/code/files/includes/cost_centre_assign.php.md), [includes/services/asset_tags.php](/code/files/includes/services/asset_tags.php.md), [includes/leases.php](/code/files/includes/leases.php.md), [includes/asset_locations.php](/code/files/includes/asset_locations.php.md), [includes/asset_warranty_calendar.php](/code/files/includes/asset_warranty_calendar.php.md), [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md)

**Included by:** [api/assets/assign_asset_user.php](/code/files/api/assets/assign_asset_user.php.md), [api/assets/create_asset.php](/code/files/api/assets/create_asset.php.md), [api/assets/email_handover.php](/code/files/api/assets/email_handover.php.md), [api/assets/get_people.php](/code/files/api/assets/get_people.php.md), [api/assets/get_users_with_assets.php](/code/files/api/assets/get_users_with_assets.php.md), [api/assets/get_user_assets.php](/code/files/api/assets/get_user_assets.php.md), [api/assets/handover_templates.php](/code/files/api/assets/handover_templates.php.md), [api/assets/move_to_company.php](/code/files/api/assets/move_to_company.php.md), [api/assets/unassign_asset_user.php](/code/files/api/assets/unassign_asset_user.php.md), [api/assets/update_asset_field.php](/code/files/api/assets/update_asset_field.php.md), [api/external/software-inventory/submit/index.php](/code/files/api/external/software-inventory/submit/index.php.md), [api/external/system-info/submit/index.php](/code/files/api/external/system-info/submit/index.php.md), [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md), [asset-management/handover.php](/code/files/asset-management/handover.php.md), [includes/intune.php](/code/files/includes/intune.php.md), [includes/leases.php](/code/files/includes/leases.php.md), [includes/services/asset_import.php](/code/files/includes/services/asset_import.php.md)

**Calls:** [activeTenantFilter()](/code/functions/activeTenantFilter.md), [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [analystCanAccessUser()](/code/functions/analystCanAccessUser.md), [assetLocationScope()](/code/functions/assetLocationScope.md), [costCentreCheck()](/code/functions/costCentreCheck.md), [costCentreLabel()](/code/functions/costCentreLabel.md), [costCentreReady()](/code/functions/costCentreReady.md), [costCentreRecordTenant()](/code/functions/costCentreRecordTenant.md), [costCentreRef()](/code/functions/costCentreRef.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [getTenantById()](/code/functions/getTenantById.md), [getTenantConfigRows()](/code/functions/getTenantConfigRows.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [leaseActiveSql()](/code/functions/leaseActiveSql.md), [leaseAssetDatesEdited()](/code/functions/leaseAssetDatesEdited.md), [leaseReady()](/code/functions/leaseReady.md), [syncAssetWarrantyCalendar()](/code/functions/syncAssetWarrantyCalendar.md), [userDirectoryOwnedFields()](/code/functions/userDirectoryOwnedFields.md), [userSourceFilter()](/code/functions/userSourceFilter.md)


# AssetsService

> Class in [includes/services/assets.php](/code/files/includes/services/assets.php.md), line 38

## Methods

| Method | What it does |
|---|---|
| [assertScope()](#assertscope) | Multi-tenancy gate: refuse to touch an asset outside the actor's companies. companyScope null = all companies (single-company install or an all-access actor) → no gate. Framed as not-found so it never reveals another company's asset. `$row` must include tenant_id. |
| [fieldMap()](#fieldmap) | The editable columns, their audit field keys (the SAME stable keys the UI history view localises via t('asset-management.field.<key>')), and how to validate/resolve each. 'lookup'/'supplier' fields audit display NAMES. |
| [createAsset()](#createasset) | Create an asset (identified by its unique hostname). Returns the new id. `$creationNote` is the audit new_value for the 'asset_created' row (the API records the acting key, the UI records the analyst — see api/assets/create_asset.php, added in #1132 because a television cannot run the inventory agent). |
| [updateFields()](#updatefields) | Apply a partial set of field updates to an asset. Writes one audit row per changed field (no-ops are skipped) and re-syncs the warranty calendar when warranty_expiry moves. Returns void; the adapter reloads for its response. |
| [assignAnalyst()](#assignanalyst) | Assign an ANALYST to an asset — the desk's own kit. |
| [assignUser()](#assignuser) | Assign a requester to an asset. `$in`: user_id \| user_email, plus optional notes, expected_return_date, previous_user_id (UI re-assign old_value). Returns [asset_id, user_id, name, expected_return_date, notes]. |
| [unassignUser()](#unassignuser) | Remove a requester from an asset. `$skipAudit` suppresses the history row (the UI's re-assign removes the previous holder silently, then the assign logs the transition). Returns [asset_id, user_id]. |
| [unassignAnalyst()](#unassignanalyst) | Remove an analyst from an asset — the mirror of `unassignUser()`. |
| [dispatch()](#dispatch) | Fire an asset.* workflow event (best-effort; the engine swallows its own errors). |
| [moveToCompany()](#movetocompany) | Move an asset to another company (2.6.0, from a customer's request list). |
| [loadRow()](#loadrow) | Load the base asset row for write guards + change detection; 404 if unknown. |
| [auditWrite()](#auditwrite) | Records one field change in the asset's history; analyst 0 (an inventory source or Intune) is stored as NULL. |
| [parseDate()](#parsedate) | Validate a DATE field (YYYY-MM-DD); 422 naming the field. Null/'' clears. |
| [validateField()](#validatefield) | Validate one incoming field value per its map entry. Returns the DB-ready value. |
| [assertLeaseOrder()](#assertleaseorder) | A lease cannot end before it starts. Checked on the dates as they will be after the write (what was sent, else what is stored), so changing either one alone is held to the other. |
| [auditDisplay()](#auditdisplay) | Resolve a lookup id to its display name for the audit trail. |
| [syncWarranty()](#syncwarranty) | Re-sync the asset expiry calendar (best-effort; same hook the UI + API used). One pass writes BOTH warranty and lease entries, which is why there is no lease equivalent of this method. |
| [usersHoldingAssets()](#usersholdingassets) | Everyone who currently holds at least one asset, with how many. |
| [people()](#people) | Everyone, whether or not they hold anything. |
| [assetsForUser()](#assetsforuser) | One person, and everything currently assigned to them. |
| [personName()](#personname) | Best available human name for a requester row, falling back to the email. |
| [getIgnoredServiceTags()](#getignoredservicetags) | The placeholder list as a lookup map [lowercased => true]. The stored setting, one per line, replaces the defaults when it has anything in it. |
| [isUsableServiceTag()](#isusableservicetag) | Is this serial worth matching on - not blank, not a placeholder? |
| [resolveAssetIdentity()](#resolveassetidentity) | Which existing asset, if any, an incoming device is. Reads only. |
| [updateAssetHostname()](#updateassethostname) | Apply a rename an inventory source reported for an asset it has matched by something stronger than its name. Skipped, and reported as a conflict, if another asset in the SAME company (the asset's own, wherever a person may have moved it) already has the name - hostname is unique per company. |
| [reconcileAsset()](#reconcileasset) | Resolve, then - for a match made by link or serial - apply a reported rename. The single entry point every inventory source uses. |
| [createDiscoveredAsset()](#creatediscoveredasset) | Create an asset an inventory source has found - the inventory agents and Intune. The one place such an asset is written, so the tag, the transaction and the history row are the same whichever source found it (the three used to INSERT for themselves). |

### assertScope

*private static* - line 48

Multi-tenancy gate: refuse to touch an asset outside the actor's companies. companyScope null = all companies (single-company install or an all-access actor) → no gate. Framed as not-found so it never reveals another company's asset. `$row` must include tenant_id.

```php
function assertScope(PDO $conn, ActorContext $ctx, array $row): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$row` | `array` |  | the asset row; must include tenant_id |

### fieldMap

*public static* - line 64

The editable columns, their audit field keys (the SAME stable keys the UI history view localises via t('asset-management.field.<key>')), and how to validate/resolve each. 'lookup'/'supplier' fields audit display NAMES.

```php
function fieldMap(): array
```

### createAsset

*public static* - line 113

Create an asset (identified by its unique hostname). Returns the new id. `$creationNote` is the audit new_value for the 'asset_created' row (the API records the acting key, the UI records the analyst — see api/assets/create_asset.php, added in #1132 because a television cannot run the inventory agent).

```php
function createAsset(
    PDO $conn,
    ActorContext $ctx,
    array $in,
    string $creationNote,
    ?int $tenantId = null
): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the new asset's fields: hostname (required), any `fieldMap()` key, and asset_tag |
| `$creationNote` | `string` |  | the new_value of the 'asset_created' history row, e.g. who or what created it |
| `$tenantId` | `?int` | `null` | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 133 | `AssetsService::createAsset` | read [assets](/code/tables/assets.md) |  |
| 191 | `AssetsService::createAsset` | **insert** [assets](/code/tables/assets.md) |  |

**Called from:** [api/assets/create_asset.php](/code/files/api/assets/create_asset.php.md), [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md), [includes/services/asset_import.php](/code/files/includes/services/asset_import.php.md)

### updateFields

*public static* - line 214

Apply a partial set of field updates to an asset. Writes one audit row per changed field (no-ops are skipped) and re-syncs the warranty calendar when warranty_expiry moves. Returns void; the adapter reloads for its response.

```php
function updateFields(PDO $conn, ActorContext $ctx, int $assetId, array $in): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$assetId` | `int` |  | The id of the asset. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | [fieldMap() key => new value]; unknown keys are ignored, '' or null clears a field |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 248 | `AssetsService::updateFields` | read [assets](/code/tables/assets.md) |  |
| 286 | `AssetsService::updateFields` | **update** [assets](/code/tables/assets.md) |  |

**Called from:** [api/assets/update_asset_field.php](/code/files/api/assets/update_asset_field.php.md), [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md), [includes/leases.php](/code/files/includes/leases.php.md), [includes/services/asset_import.php](/code/files/includes/services/asset_import.php.md)

### assignAnalyst

*public static* - line 322

Assign an ANALYST to an asset — the desk's own kit.

🔑 Why this exists at all. Assets could only be held by a REQUESTER, and
analysts are not requesters: on a real install five of seven analysts had
no `users` row, so most of the desk could not be recorded as holding
anything. Asked for in the 2.5.0 request list.

🔴 EXACTLY ONE HOLDER COLUMN IS SET. A row names a requester or an
analyst, never both. The database cannot express that (a CHECK across two
columns is not portable to every MySQL version supported here), so it is
enforced here and nowhere else — which is precisely why assignment must
go through this service rather than an INSERT somewhere convenient.

`$in`: analyst_id | analyst_email, plus optional notes,
expected_return_date, previous_analyst_id.

```php
function assignAnalyst(PDO $conn, ActorContext $ctx, int $assetId, array $in): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$assetId` | `int` |  | The id of the asset. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | analyst_id or analyst_email, plus optional notes, expected_return_date (Y-m-d), previous_analyst_id |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 328 | `AssetsService::assignAnalyst` | read [analysts](/code/tables/analysts.md) |  |
| 331 | `AssetsService::assignAnalyst` | read [analysts](/code/tables/analysts.md) |  |
| 348 | `AssetsService::assignAnalyst` | read [users_assets](/code/tables/users_assets.md) |  |
| 357 | `AssetsService::assignAnalyst` | **insert** [users_assets](/code/tables/users_assets.md) (`asset_id`, `analyst_id`, `assigned_by_analyst_id`, `notes`, `expected_return_date`, `assigned_datetime`) |  |
| 366 | `AssetsService::assignAnalyst` | **insert** [asset_checkout_log](/code/tables/asset_checkout_log.md) (`asset_id`, `user_id`, `user_name`, `action`, `expected_return_date`, `analyst_id`, `notes`, `action_datetime`) |  |
| 373 | `AssetsService::assignAnalyst` | read [analysts](/code/tables/analysts.md) |  |

**Called from:** [api/assets/assign_asset_user.php](/code/files/api/assets/assign_asset_user.php.md)

### assignUser

*public static* - line 398

Assign a requester to an asset. `$in`: user_id | user_email, plus optional notes, expected_return_date, previous_user_id (UI re-assign old_value). Returns [asset_id, user_id, name, expected_return_date, notes].

```php
function assignUser(PDO $conn, ActorContext $ctx, int $assetId, array $in): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$assetId` | `int` |  | The id of the asset. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | user_id or user_email, plus optional notes, expected_return_date (Y-m-d), previous_user_id |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 405 | `AssetsService::assignUser` | read [users](/code/tables/users.md) |  |
| 408 | `AssetsService::assignUser` | read [users](/code/tables/users.md) |  |
| 423 | `AssetsService::assignUser` | read [users_assets](/code/tables/users_assets.md) |  |
| 430 | `AssetsService::assignUser` | **insert** [users_assets](/code/tables/users_assets.md) (`asset_id`, `user_id`, `assigned_by_analyst_id`, `notes`, `expected_return_date`, `assigned_datetime`) |  |
| 437 | `AssetsService::assignUser` | **insert** [asset_checkout_log](/code/tables/asset_checkout_log.md) (`asset_id`, `user_id`, `user_name`, `action`, `expected_return_date`, `analyst_id`, `notes`, `action_datetime`) |  |
| 445 | `AssetsService::assignUser` | read [users](/code/tables/users.md) |  |

**Called from:** [api/assets/assign_asset_user.php](/code/files/api/assets/assign_asset_user.php.md), [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md)

### unassignUser

*public static* - line 471

Remove a requester from an asset. `$skipAudit` suppresses the history row (the UI's re-assign removes the previous holder silently, then the assign logs the transition). Returns [asset_id, user_id].

```php
function unassignUser(
    PDO $conn,
    ActorContext $ctx,
    int $assetId,
    int $userId,
    bool $skipAudit = false
): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$assetId` | `int` |  | The id of the asset. *([standard](/handbook/standard-parameters.md))* |
| `$userId` | `int` |  | the requester (users.id) to remove from the asset |
| `$skipAudit` | `bool` | `false` | true to write no history row (a re-assign logs the change itself) |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 480 | `AssetsService::unassignUser` | read [users_assets](/code/tables/users_assets.md), read [users](/code/tables/users.md) |  |
| 490 | `AssetsService::unassignUser` | **delete** [users_assets](/code/tables/users_assets.md) |  |
| 494 | `AssetsService::unassignUser` | **insert** [asset_checkout_log](/code/tables/asset_checkout_log.md) (`asset_id`, `user_id`, `user_name`, `action`, `expected_return_date`, `analyst_id`, `action_datetime`) |  |

**Called from:** [api/assets/unassign_asset_user.php](/code/files/api/assets/unassign_asset_user.php.md), [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md)

### unassignAnalyst

*public static* - line 517

Remove an analyst from an asset — the mirror of `unassignUser()`.

Separate for the same reason `assignAnalyst()` is: it matches on a different
column, and a shared function would be an `if` at every line.

```php
function unassignAnalyst(
    PDO $conn,
    ActorContext $ctx,
    int $assetId,
    int $analystId,
    bool $skipAudit = false
): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$assetId` | `int` |  | The id of the asset. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | the analyst HOLDING the asset who is removed - not the one acting, who is in `$ctx` |
| `$skipAudit` | `bool` | `false` | true to write no history row (a re-assign logs the change itself) |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 523 | `AssetsService::unassignAnalyst` | read [users_assets](/code/tables/users_assets.md), read [analysts](/code/tables/analysts.md) |  |
| 533 | `AssetsService::unassignAnalyst` | **delete** [users_assets](/code/tables/users_assets.md) |  |
| 541 | `AssetsService::unassignAnalyst` | **insert** [asset_checkout_log](/code/tables/asset_checkout_log.md) (`asset_id`, `user_id`, `user_name`, `action`, `expected_return_date`, `analyst_id`, `action_datetime`) |  |

**Called from:** [api/assets/unassign_asset_user.php](/code/files/api/assets/unassign_asset_user.php.md)

### dispatch

*private static* - line 562

Fire an asset.* workflow event (best-effort; the engine swallows its own errors).

```php
function dispatch(string $event, PDO $conn, int $assetId, int $userId, ?string $userName): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$event` | `string` |  | the workflow event, 'asset.assigned' or 'asset.unassigned' |
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$assetId` | `int` |  | The id of the asset. *([standard](/handbook/standard-parameters.md))* |
| `$userId` | `int` |  | the requester holding it, or 0 when the holder is an analyst |
| `$userName` | `?string` |  | the holder's display name |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 565 | `AssetsService::dispatch` | read [assets](/code/tables/assets.md) |  |

### moveToCompany

*public static* - line 603

Move an asset to another company (2.6.0, from a customer's request list).

The asset twin of TasksService::moveTaskToCompany, gated the same way: the
actor must reach the asset where it is AND the company it is going to,
checked against BOTH companyScope (what this caller may touch) and
analystCanAccessTenant (what the person may reach).

An asset carries more company-owned things than a task, and they split
into two kinds on purpose:

 CLEARED, and reported: its LOCATION, TYPE and STATUS when they are the old
 company's own. They simply do not exist in the new company, so keeping them
 would leave the asset wearing a value its own company cannot see or pick.
 A shared location, and a global type or status, move with it untouched.

 REFUSED, with the reason: a HOSTNAME or ASSET TAG the new company already
 uses (both are unique per company, and agent ingest matches on hostname),
 and a HOLDER who is a person in another company. Those need a decision
 from the person, not a guess made on their behalf.

An analyst holder moves with it: analysts are not company-scoped.

```php
function moveToCompany(PDO $conn, ActorContext $ctx, int $assetId, int $targetTenantId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$assetId` | `int` |  | The id of the asset. *([standard](/handbook/standard-parameters.md))* |
| `$targetTenantId` | `int` |  | the company (tenant) id to move the asset to |

**Returns:** array{moved:bool, from:string, to:string, cleared:string[]} `cleared` lists the audit keys (location/type/status) that were emptied, so the UI can say what changed.

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 636 | `AssetsService::moveToCompany` | read [assets](/code/tables/assets.md) |  |
| 643 | `AssetsService::moveToCompany` | read [assets](/code/tables/assets.md) |  |
| 652 | `AssetsService::moveToCompany` | read [users_assets](/code/tables/users_assets.md), read [users](/code/tables/users.md) |  |
| 666 | `AssetsService::moveToCompany` | read [lease_assets](/code/tables/lease_assets.md), read [lease_schedules](/code/tables/lease_schedules.md), read [leases](/code/tables/leases.md), read [lease_outcomes](/code/tables/lease_outcomes.md) |  |
| 679 | `AssetsService::moveToCompany` | read [asset_locations](/code/tables/asset_locations.md) |  |
| 699 | `AssetsService::moveToCompany` | **update** [assets](/code/tables/assets.md) |  |

**Called from:** [api/assets/move_to_company.php](/code/files/api/assets/move_to_company.php.md)

### loadRow

*private static* - line 720

Load the base asset row for write guards + change detection; 404 if unknown.

```php
function loadRow(PDO $conn, int $assetId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$assetId` | `int` |  | The id of the asset. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 722 | `AssetsService::loadRow` | read [assets](/code/tables/assets.md) |  |

### auditWrite

*private static* - line 739

Records one field change in the asset's history; analyst 0 (an inventory source or Intune) is stored as NULL.

```php
function auditWrite(
    PDO $conn,
    int $assetId,
    int $analystId,
    string $fieldKey,
    ?string $old,
    ?string $new
): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$assetId` | `int` |  | The id of the asset. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$fieldKey` | `string` |  | the history field_name - a `fieldMap()` audit key or an event such as 'asset_created' |
| `$old` | `?string` |  | the value before, as shown in history (null = not set) |
| `$new` | `?string` |  | the value after, as shown in history (null = not set) |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 745 | `AssetsService::auditWrite` | **insert** [asset_history](/code/tables/asset_history.md) (`asset_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |

### parseDate

*private static* - line 756

Validate a DATE field (YYYY-MM-DD); 422 naming the field. Null/'' clears.

```php
function parseDate($value, string $field): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$value` |  |  | the date from the request, Y-m-d; null or '' gives null |
| `$field` | `string` |  | the request field name, used in the error message |

### validateField

*private static* - line 775

Validate one incoming field value per its map entry. Returns the DB-ready value.

```php
function validateField(PDO $conn, string $field, $value, array $def)
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$field` | `string` |  | the asset column being set, a `fieldMap()` key |
| `$value` |  |  | the value from the request; '' or null gives null |
| `$def` | `array` |  | that column's `fieldMap()` entry (kind, table, label, max) |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 789 | `AssetsService::validateField` | read [suppliers](/code/tables/suppliers.md) |  |

### assertLeaseOrder

*private static* - line 824

A lease cannot end before it starts. Checked on the dates as they will be after the write (what was sent, else what is stored), so changing either one alone is held to the other.

```php
function assertLeaseOrder(array $in, array $current): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$in` | `array` |  | the fields being written; lease_start / lease_expiry here win |
| `$current` | `array` |  | the asset row as stored ([] on create) |

### auditDisplay

*private static* - line 844

Resolve a lookup id to its display name for the audit trail.

```php
function auditDisplay(PDO $conn, string $field, $value, array $def): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$field` | `string` |  | the asset column, a `fieldMap()` key |
| `$value` |  |  | the stored value - an id for lookup, supplier and cost centre columns |
| `$def` | `array` |  | that column's `fieldMap()` entry |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 860 | `AssetsService::auditDisplay` | read [suppliers](/code/tables/suppliers.md) |  |

### syncWarranty

*private static* - line 873

Re-sync the asset expiry calendar (best-effort; same hook the UI + API used). One pass writes BOTH warranty and lease entries, which is why there is no lease equivalent of this method.

```php
function syncWarranty(PDO $conn): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

### usersHoldingAssets

*public static* - line 899

Everyone who currently holds at least one asset, with how many.

The list is driven by `users_assets`, not by `users`: the question being
answered is "who has kit", so somebody with nothing does not belong on the
list at all. Search is applied here rather than client-side because an
install with thousands of requesters should not ship them all to a browser.

⚠️ INNER JOIN to users on purpose. users_assets has no foreign key, and
older installs carry rows pointing at requesters that no longer exist —
Ed's own dev database has nine. A LEFT JOIN would list them as blank people
holding real equipment, which reads as data loss rather than as stale rows.

```php
function usersHoldingAssets(
    PDO $conn,
    ActorContext $ctx,
    string $search = '',
    int $limit = 200
): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$search` | `string` | `''` | text to match in the person's name or email; '' for everyone |
| `$limit` | `int` | `200` | the most people to return (1-500) |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 913 | `AssetsService::usersHoldingAssets` | read [users_assets](/code/tables/users_assets.md), read [users](/code/tables/users.md), read [assets](/code/tables/assets.md) |  |

**Called from:** [api/assets/get_users_with_assets.php](/code/files/api/assets/get_users_with_assets.php.md)

### people

*public static* - line 968

Everyone, whether or not they hold anything.

The companion to `usersHoldingAssets()`, and a different question: that one
answers "who has equipment", this one answers "who is there". You cannot
assign a laptop to somebody the list will not show you, so a people
directory that only lists current holders is unusable for the one job it
most needs to do — issuing kit to a new starter.

`$scope`:
  'current'  — people who are still here. The default: a leaver in a
               picker is how equipment gets issued to somebody who left.
  'leavers'  — only those marked as having left.
  'everyone' — genuinely everyone, both of the above.
  'holding'  — anybody holding equipment, INCLUDING leavers. A leaver who
               still has a laptop is the single most actionable row on
               this screen, so a filter about equipment must not hide them
               behind a filter about employment.

⚠️ 'everyone' means everyone. It was originally the default and excluded
leavers, which is a contradiction — a filter that says Everyone and hides
people teaches you not to trust the others either.

Tenancy is applied to the PERSON (users.tenant_id), not to their assets —
otherwise somebody who holds nothing would fall outside the filter and
silently vanish from the directory.

```php
function people(
    PDO $conn,
    ActorContext $ctx,
    string $search = '',
    string $scope = 'current',
    int $limit = 500,
    string $source = ''
): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$search` | `string` | `''` | text to match in name, email, username, department, job title or employee id; '' for all |
| `$scope` | `string` | `'current'` | 'current' (default), 'leavers', 'everyone' or 'holding' - see above |
| `$limit` | `int` | `500` | the most people to return (1-1000) |
| `$source` | `string` | `''` | '' for every source, 'local' for people linked to nothing, or an auth_providers id |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1000 | `AssetsService::people` | read [users_assets](/code/tables/users_assets.md) |  |
| 1008 | `AssetsService::people` | read [users_assets](/code/tables/users_assets.md), read [users](/code/tables/users.md), read [auth_providers](/code/tables/auth_providers.md) |  |

**Called from:** [api/assets/get_people.php](/code/files/api/assets/get_people.php.md)

### assetsForUser

*public static* - line 1061

One person, and everything currently assigned to them.

Returns ['user' => …, 'assets' => […]] or null when the person does not
exist. An existing person holding nothing returns an empty asset list
rather than null — "Ada has no equipment" is a real and useful answer,
particularly during offboarding.

```php
function assetsForUser(PDO $conn, ActorContext $ctx, int $userId): ?array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$userId` | `int` |  | the requester (users.id) to show |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1081 | `AssetsService::assetsForUser` | read [users](/code/tables/users.md), read [auth_providers](/code/tables/auth_providers.md) |  |
| 1119 | `AssetsService::assetsForUser` | read [users](/code/tables/users.md) |  |
| 1135 | `AssetsService::assetsForUser` | read [users_assets](/code/tables/users_assets.md), read [assets](/code/tables/assets.md), read [asset_types](/code/tables/asset_types.md), read [asset_status_types](/code/tables/asset_status_types.md), read [asset_locations](/code/tables/asset_locations.md), read [analysts](/code/tables/analysts.md) |  |
| 1175 | `AssetsService::assetsForUser` | read [asset_field_sets](/code/tables/asset_field_sets.md) |  |

**Called from:** [api/assets/email_handover.php](/code/files/api/assets/email_handover.php.md), [api/assets/get_user_assets.php](/code/files/api/assets/get_user_assets.php.md), [api/assets/handover_templates.php](/code/files/api/assets/handover_templates.php.md), [asset-management/handover.php](/code/files/asset-management/handover.php.md)

### personName

*private static* - line 1197

Best available human name for a requester row, falling back to the email.

```php
function personName(array $row): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$row` | `array` |  | a users row with display_name, preferred_name, email and id |

### getIgnoredServiceTags

*public static* - line 1231

The placeholder list as a lookup map [lowercased => true]. The stored setting, one per line, replaces the defaults when it has anything in it.

```php
function getIgnoredServiceTags(PDO $conn): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1238 | `AssetsService::getIgnoredServiceTags` | read [system_settings](/code/tables/system_settings.md) |  |

**Called from:** [includes/intune.php](/code/files/includes/intune.php.md)

### isUsableServiceTag

*public static* - line 1258

Is this serial worth matching on - not blank, not a placeholder?

```php
function isUsableServiceTag(?string $tag, ?array $ignoredMap = null): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$tag` | `?string` |  | the serial number reported |
| `$ignoredMap` | `?array` | `null` | placeholder serials as [lowercased => true], from `getIgnoredServiceTags()`; null uses the defaults |

### resolveAssetIdentity

*public static* - line 1291

Which existing asset, if any, an incoming device is. Reads only.

Tier 1  an authoritative connector link the caller already holds
  Tier 2  a usable serial - unless two assets share it (ambiguity guard)
  Tier 3  hostname - unless a usable serial CONTRADICTS it (below)
  Tier 4  nothing: a new asset

🔴 THE LAPTOP REFRESH. A replacement laptop is very often given the old
one's name. It reports a good serial nobody has seen, so tier 2 finds
nothing - and a plain hostname match would then put the NEW serial on the
OLD laptop's record, leaving one record describing two machines. So a
hostname match whose asset already holds a different usable serial is a
different machine: tier 4, flagged `hostname_reused`. An asset entered
without a serial (by hand, before the agent ran) still matches by name -
that is how the agent finds it the first time.

```php
function resolveAssetIdentity(
    PDO $conn,
    array $ids,
    ?int $tenantId = null,
    bool $isExplicitLink = false,
    ?array $ignoredTags = null,
    bool $anyCompany = false
): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ids` | `array` |  | asset_id (tier 1, with `$isExplicitLink`), service_tag, hostname |
| `$tenantId` | `?int` | `null` | the company to look in; NULL = the Default company |
| `$isExplicitLink` | `bool` | `false` | true when `$ids`['asset_id'] is a stored connector link to trust (tier 1) |
| `$ignoredTags` | `?array` | `null` | placeholder serials from `getIgnoredServiceTags()`; null reads the setting |
| `$anyCompany` | `bool` | `false` | look in EVERY company (tiers 2-3). Only for a connection that has no company of its own - see `intuneLinkDevicesToAssets()`. |

**Returns:** array{asset_id:?int, matched_by:string, ambiguous:bool, hostname_reused:?int}

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1304 | `AssetsService::resolveAssetIdentity` | read [assets](/code/tables/assets.md) |  |
| 1315 | `AssetsService::resolveAssetIdentity` | read [assets](/code/tables/assets.md) |  |
| 1321 | `AssetsService::resolveAssetIdentity` | read [assets](/code/tables/assets.md) |  |
| 1331 | `AssetsService::resolveAssetIdentity` | read [assets](/code/tables/assets.md) |  |

### updateAssetHostname

*public static* - line 1355

Apply a rename an inventory source reported for an asset it has matched by something stronger than its name. Skipped, and reported as a conflict, if another asset in the SAME company (the asset's own, wherever a person may have moved it) already has the name - hostname is unique per company.

```php
function updateAssetHostname(
    PDO $conn,
    ActorContext $ctx,
    int $assetId,
    string $newHostname,
    string $source = 'system'
): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$assetId` | `int` |  | The id of the asset. *([standard](/handbook/standard-parameters.md))* |
| `$newHostname` | `string` |  | the name the inventory source reported (cut to 50 characters) |
| `$source` | `string` | `'system'` | what reported it, e.g. 'Intune', added to the history row |

**Returns:** array{updated:bool, conflict:bool}

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1360 | `AssetsService::updateAssetHostname` | read [assets](/code/tables/assets.md) |  |
| 1367 | `AssetsService::updateAssetHostname` | read [assets](/code/tables/assets.md) |  |
| 1371 | `AssetsService::updateAssetHostname` | **update** [assets](/code/tables/assets.md) (`hostname`) |  |

**Called from:** [includes/intune.php](/code/files/includes/intune.php.md)

### reconcileAsset

*public static* - line 1394

Resolve, then - for a match made by link or serial - apply a reported rename. The single entry point every inventory source uses.

```php
function reconcileAsset(
    PDO $conn,
    ActorContext $ctx,
    array $ids,
    ?int $tenantId = null,
    string $source = 'system',
    bool $isExplicitLink = false,
    ?array $ignoredTags = null,
    bool $anyCompany = false
): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$ids` | `array` |  | what the source reported: asset_id (with `$isExplicitLink`), service_tag, hostname |
| `$tenantId` | `?int` | `null` | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$source` | `string` | `'system'` | what reported it, e.g. 'software inventory', added to a rename's history row |
| `$isExplicitLink` | `bool` | `false` | true when `$ids`['asset_id'] is a stored connector link to trust |
| `$ignoredTags` | `?array` | `null` | placeholder serials from `getIgnoredServiceTags()`; null reads the setting |
| `$anyCompany` | `bool` | `false` | true to match across every company - only for a connection with no company of its own |

**Returns:** array{asset_id:?int, matched_by:string, ambiguous:bool, hostname_reused:?int, hostname_updated:bool, hostname_conflict:bool}

**Called from:** [api/external/software-inventory/submit/index.php](/code/files/api/external/software-inventory/submit/index.php.md), [api/external/system-info/submit/index.php](/code/files/api/external/system-info/submit/index.php.md), [includes/intune.php](/code/files/includes/intune.php.md)

### createDiscoveredAsset

*public static* - line 1419

Create an asset an inventory source has found - the inventory agents and Intune. The one place such an asset is written, so the tag, the transaction and the history row are the same whichever source found it (the three used to INSERT for themselves).

```php
function createDiscoveredAsset(
    PDO $conn,
    ActorContext $ctx,
    array $fields,
    ?int $tenantId,
    string $source,
    ?int $hostnameReusedBy = null
): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$fields` | `array` |  | column => value, from the discovered-asset columns below |
| `$tenantId` | `?int` |  | the company it belongs to (NULL = Default) |
| `$source` | `string` |  | what found it, e.g. 'the software inventory agent' - the history row's value |
| `$hostnameReusedBy` | `?int` | `null` | the asset a refreshed machine's name still belongs to, if any |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1437 | `AssetsService::createDiscoveredAsset` | **insert** [assets](/code/tables/assets.md) |  |

**Called from:** [api/external/software-inventory/submit/index.php](/code/files/api/external/software-inventory/submit/index.php.md), [api/external/system-info/submit/index.php](/code/files/api/external/system-info/submit/index.php.md), [includes/intune.php](/code/files/includes/intune.php.md)


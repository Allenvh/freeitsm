# AssetTagsService

> Class in [includes/services/asset_tags.php](/code/files/includes/services/asset_tags.php.md), line 46

## Methods

| Method | What it does |
|---|---|
| [withSettings()](#withsettings) | Run with a specific configuration — used by the tests. |
| [forget()](#forget) | Drop the override and the settings cache. |
| [config()](#config) | The effective configuration for one company (null = Default). |
| [isAutogenEnabled()](#isautogenenabled) | True when asset tags are generated automatically for the company. |
| [validateFormat()](#validateformat) | What is wrong with a format, in words an administrator can act on. Empty means it is usable. |
| [preview()](#preview) | A few example tags for a proposed configuration. Writes nothing. |
| [storeTenant()](#storetenant) | Assets store the Default company as NULL; so does every check here. |
| [counterKey()](#counterkey) | Which counter a company draws from. |
| [nextNumber()](#nextnumber) | The number the next generated tag will carry. Reads only. |
| [setNextNumber()](#setnextnumber) | Make the next generated tag at least `$next`. Only ever forward. |
| [windCounterTo()](#windcounterto) | Moves the tag counter forward to `$lastIssued`, never back. |
| [claimNext()](#claimnext) | Claim the next number. Same statement, and the same `rowCount()` trap, as TicketNumbering::claimNext(). |
| [generate()](#generate) | The next generated tag for a company. Call it inside `withLock()` and inside the transaction that writes the asset, so a failed write gives the number back. Uniqueness is proven against the assets, never assumed from the counter: a counter can be behind (a restored backup, tags typed by hand). |
| [noteManualTag()](#notemanualtag) | A tag typed by hand that has the generated SHAPE and a number ahead of the counter winds the counter past it, so the generator never has to skip it later. Anything else is left alone. |
| [withLock()](#withlock) | Run `$fn` holding the per-company tag lock. Fails closed: a lock that cannot be had is an error, never "carry on unguarded". |
| [clashMessage()](#clashmessage) | The plain message for a tag that is taken, naming where. |
| [createWithTag()](#createwithtag) | Create an asset that may carry a tag: typed in (`$manualTag`), generated (when switched on for the company and nothing was typed), or none. Takes the lock when a tag is involved, and runs `$insert` inside a transaction - the caller's, if it already has one - so the counter claim, the insert and whatever `$insert` audits succeed or fail together. |
| [assign()](#assign) | Change an existing asset's tag (the box on the asset page). Blank clears it. Recorded in the asset's history. The caller has already checked the analyst may see the asset. |

### withSettings

*public static* - line 73

Run with a specific configuration — used by the tests.

```php
function withSettings(?array $settings): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$settings` | `?array` |  | setting keys (as DEFAULTS) to use instead of the stored ones; null goes back to the stored ones |

### forget

*public static* - line 79

Drop the override and the settings cache.

```php
function forget(): void
```

**Called from:** [api/assets/save_asset_tag_settings.php](/code/files/api/assets/save_asset_tag_settings.php.md)

### config

*public static* - line 90

The effective configuration for one company (null = Default).

```php
function config(PDO $conn, ?int $tenantId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Called from:** [api/assets/get_asset_tag_settings.php](/code/files/api/assets/get_asset_tag_settings.php.md), [api/assets/save_asset_tag_settings.php](/code/files/api/assets/save_asset_tag_settings.php.md)

### isAutogenEnabled

*public static* - line 105

True when asset tags are generated automatically for the company.

```php
function isAutogenEnabled(PDO $conn, ?int $tenantId): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

### validateFormat

*public static* - line 116

What is wrong with a format, in words an administrator can act on. Empty means it is usable.

```php
function validateFormat(string $format): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$format` | `string` |  | the proposed tag format, e.g. 'AST-{#####}' |

**Called from:** [api/assets/asset_tag_preview.php](/code/files/api/assets/asset_tag_preview.php.md), [api/assets/save_asset_tag_settings.php](/code/files/api/assets/save_asset_tag_settings.php.md)

### preview

*public static* - line 150

A few example tags for a proposed configuration. Writes nothing.

```php
function preview(array $cfg, int $start, int $count = 3): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$cfg` | `array` |  | the proposed settings; only asset_tag_format is used |
| `$start` | `int` |  | the number of the first example (at least 1) |
| `$count` | `int` | `3` | how many examples to return |

**Called from:** [api/assets/asset_tag_preview.php](/code/files/api/assets/asset_tag_preview.php.md), [api/assets/get_asset_tag_settings.php](/code/files/api/assets/get_asset_tag_settings.php.md), [api/assets/save_asset_tag_settings.php](/code/files/api/assets/save_asset_tag_settings.php.md)

### storeTenant

*public static* - line 167

Assets store the Default company as NULL; so does every check here.

```php
function storeTenant(PDO $conn, ?int $tenantId): ?int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Called from:** [includes/services/assets.php](/code/files/includes/services/assets.php.md)

### counterKey

*public static* - line 178

Which counter a company draws from.

```php
function counterKey(PDO $conn, array $cfg, ?int $tenantId): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$cfg` | `array` |  | the company's settings from `config()`; asset_tag_scope 'global' shares one counter |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

### nextNumber

*public static* - line 187

The number the next generated tag will carry. Reads only.

```php
function nextNumber(PDO $conn, ?int $tenantId): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 191 | `AssetTagsService::nextNumber` | read [asset_tag_counters](/code/tables/asset_tag_counters.md) |  |

**Called from:** [api/assets/get_asset_tag_settings.php](/code/files/api/assets/get_asset_tag_settings.php.md), [api/assets/save_asset_tag_settings.php](/code/files/api/assets/save_asset_tag_settings.php.md)

### setNextNumber

*public static* - line 206

Make the next generated tag at least `$next`. Only ever forward.

```php
function setNextNumber(PDO $conn, ?int $tenantId, int $next): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$next` | `int` |  | the number the next generated tag should carry at least |

**Called from:** [api/assets/save_asset_tag_settings.php](/code/files/api/assets/save_asset_tag_settings.php.md)

### windCounterTo

*private static* - line 218

Moves the tag counter forward to `$lastIssued`, never back.

```php
function windCounterTo(PDO $conn, string $key, int $lastIssued): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$key` | `string` |  | the asset_tag_counters key, from `counterKey()` |
| `$lastIssued` | `int` |  | the number to treat as already issued; a lower value leaves the counter alone |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 221 | `AssetTagsService::windCounterTo` | **insert** [asset_tag_counters](/code/tables/asset_tag_counters.md) (`counter_key`, `next_value`) |  |

### claimNext

*private static* - line 232

Claim the next number. Same statement, and the same `rowCount()` trap, as TicketNumbering::claimNext().

```php
function claimNext(PDO $conn, string $key, int $start): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$key` | `string` |  | the asset_tag_counters key, from `counterKey()` |
| `$start` | `int` |  | the number to issue when the counter does not exist yet |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 235 | `AssetTagsService::claimNext` | **insert** [asset_tag_counters](/code/tables/asset_tag_counters.md) (`counter_key`, `next_value`) |  |

### generate

*public static* - line 257

The next generated tag for a company. Call it inside `withLock()` and inside the transaction that writes the asset, so a failed write gives the number back. Uniqueness is proven against the assets, never assumed from the counter: a counter can be behind (a restored backup, tags typed by hand).

```php
function generate(PDO $conn, ?int $tenantId): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

### noteManualTag

*public static* - line 292

A tag typed by hand that has the generated SHAPE and a number ahead of the counter winds the counter past it, so the generator never has to skip it later. Anything else is left alone.

```php
function noteManualTag(PDO $conn, ?int $tenantId, string $tag): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$tag` | `string` |  | the tag that was typed by hand |

### withLock

*public static* - line 319

Run `$fn` holding the per-company tag lock. Fails closed: a lock that cannot be had is an error, never "carry on unguarded".

```php
function withLock(PDO $conn, ?int $tenantId, callable $fn)
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$fn` | `callable` |  | the work to do while the lock is held; what it returns is passed back |

**Returns:** T

### clashMessage

*public static* - line 341

The plain message for a tag that is taken, naming where.

```php
function clashMessage(PDO $conn, ?int $storeTenant, string $tag, ?int $exceptAssetId = null): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$storeTenant` | `?int` |  | the company as assets store it - null for the Default company |
| `$tag` | `string` |  | the tag that is already taken |
| `$exceptAssetId` | `?int` | `null` | an asset to ignore (the one being changed), or null |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 343 | `AssetTagsService::clashMessage` | read [assets](/code/tables/assets.md) |  |

### createWithTag

*public static* - line 364

Create an asset that may carry a tag: typed in (`$manualTag`), generated (when switched on for the company and nothing was typed), or none. Takes the lock when a tag is involved, and runs `$insert` inside a transaction - the caller's, if it already has one - so the counter claim, the insert and whatever `$insert` audits succeed or fail together.

```php
function createWithTag(PDO $conn, ?int $tenantId, ?string $manualTag, callable $insert): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$manualTag` | `?string` |  | the tag typed in (trimmed, up to 64 characters); null or '' generates one if switched on |
| `$insert` | `callable` |  | writes the asset with the given tag, returns its id |

**Returns:** array{id:int, tag:?string}

**Called from:** [includes/services/assets.php](/code/files/includes/services/assets.php.md)

### assign

*public static* - line 407

Change an existing asset's tag (the box on the asset page). Blank clears it. Recorded in the asset's history. The caller has already checked the analyst may see the asset.

```php
function assign(PDO $conn, ActorContext $ctx, int $assetId, string $tag): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$assetId` | `int` |  | The id of the asset. *([standard](/handbook/standard-parameters.md))* |
| `$tag` | `string` |  | the new tag as typed; '' clears it |

**Returns:** array{asset_tag:string, unchanged:bool}

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 413 | `AssetTagsService::assign` | read [assets](/code/tables/assets.md) |  |
| 427 | `AssetTagsService::assign` | **update** [assets](/code/tables/assets.md) (`asset_tag`) |  |
| 431 | `AssetTagsService::assign` | **insert** [asset_history](/code/tables/asset_history.md) (`asset_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |

**Called from:** [api/assets/save_asset_tag.php](/code/files/api/assets/save_asset_tag.php.md)


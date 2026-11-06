# AssetImportService

> Class in [includes/services/asset_import.php](/code/files/includes/services/asset_import.php.md), line 39

## Methods

| Method | What it does |
|---|---|
| [availableMatchKeys()](#availablematchkeys) | MATCH_KEYS minus anything this install has not got yet. |
| [schemaReady()](#schemaready) | True when the asset import tables exist (after Database Verification). Cached per request. |
| [readCsv()](#readcsv) | Read a CSV into [headers, rows]. Rows are [header => value]. |
| [dedupeHeaders()](#dedupeheaders) | "Serial", "Serial" -> "Serial", "Serial (2)". |
| [suggestMapping()](#suggestmapping) | Guess a mapping from the column names, so the screen opens mostly done. |
| [run()](#run) | Import rows. `$mode` of 'preview' does every check and writes nothing. |
| [processRow()](#processrow) | One row: map it, find it, write it. |
| [resolveLookups()](#resolvelookups) | Turn the NAMES a spreadsheet contains into the ids the columns hold. |
| [hasTenantColumn()](#hastenantcolumn) | Not every lookup table is company-scoped (suppliers may not be). |
| [applyMapping()](#applymapping) | Split a source row into core columns and custom field values. |
| [findMatch()](#findmatch) | Find the existing asset for this row, trying each match key IN ORDER. |
| [applyCreate()](#applycreate) | Creates an asset from one import row, with its custom fields, or describes it on a preview. |
| [applyUpdate()](#applyupdate) | Updates an existing asset from one import row, or describes it on a preview. |
| [writeFields()](#writefields) | Write the custom fields, honouring on_unknown_option. |
| [resolveOption()](#resolveoption) | A dropdown value the option list does not have (§6.7). reject -> the row fails; add -> the option is created. |
| [attachProfileSet()](#attachprofileset) | The profile's field set, applied to everything it touches. |
| [handleMissing()](#handlemissing) | Rows present last run and absent now (§6.6). |
| [inactiveStatusId()](#inactivestatusid) | The asset status to give an asset that has dropped out of the source - the first active status whose name contains retired, disposed or inactive. |
| [unresolved()](#unresolved) | Rows that could not be imported and have not been dealt with. |
| [resolveEntry()](#resolveentry) | Mark a parked row dealt with — either retried successfully, or dismissed. |
| [startRun()](#startrun) | Records the start of an import run and returns its id. |
| [finishRun()](#finishrun) | Marks an import run finished with its counts, and returns the run. |
| [loadRun()](#loadrun) | Loads one import run with its counts as integers; throws not found when it does not exist. |
| [runEntries()](#runentries) | Every row's outcome for a run. |
| [logEntry()](#logentry) | Writes one row of an import run's log. |
| [loadCore()](#loadcore) | An asset's id and core columns, for comparing with an import row. |
| [firstNonEmpty()](#firstnonempty) | The first of `$keys` that has a non-blank value in `$core`, or null. |
| [refOf()](#refof) | The value an import row is matched on - the first non-blank of the match keys. |
| [nameOf()](#nameof) | The asset name an import row maps to. |
| [describe()](#describe) | A human sentence describing what a row did, for the log. |
| [short()](#short) | A value cut to 40 characters for the run log. |

### availableMatchKeys

*public static* - line 85

MATCH_KEYS minus anything this install has not got yet.

`assets.asset_tag` arrives with the QR-label update; naming it on an
install that has pulled the code but not run Verification turns every
single row into "Unknown column" — the same trap the asset list had to
dodge.

```php
function availableMatchKeys(PDO $conn): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 92 | `AssetImportService::availableMatchKeys` | read [assets](/code/tables/assets.md) |  |

**Called from:** [api/assets/import_upload.php](/code/files/api/assets/import_upload.php.md)

### schemaReady

*public static* - line 105

True when the asset import tables exist (after Database Verification). Cached per request.

```php
function schemaReady(PDO $conn): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 110 | `AssetImportService::schemaReady` | read [asset_import_runs](/code/tables/asset_import_runs.md) |  |

**Called from:** [api/assets/import_history.php](/code/files/api/assets/import_history.php.md), [api/assets/import_upload.php](/code/files/api/assets/import_upload.php.md)

### readCsv

*public static* - line 134

Read a CSV into [headers, rows]. Rows are [header => value].

⚠️ Strips a UTF-8 BOM. Excel writes one by default, and without this the
first column's header arrives as "\xEF\xBB\xBFHostname" — which maps to
nothing, so the identity column silently goes missing and every row looks
new. It is the single most common reason a first import "does nothing".

```php
function readCsv(string $path, array $config = []): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$path` | `string` |  | the CSV file on disk |
| `$config` | `array` | `[]` | delimiter (default ','), has_header (default true; false names the columns 'Column 1'...) |

**Called from:** [api/assets/import_run.php](/code/files/api/assets/import_run.php.md), [api/assets/import_upload.php](/code/files/api/assets/import_upload.php.md)

### dedupeHeaders

*private static* - line 204

"Serial", "Serial" -> "Serial", "Serial (2)".

```php
function dedupeHeaders(array $headers): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$headers` | `array` |  | the trimmed header cells in column order |

### suggestMapping

*public static* - line 228

Guess a mapping from the column names, so the screen opens mostly done.

A suggestion only — every one is shown and overridable. Getting this
wrong silently is exactly what the preview exists to catch.

```php
function suggestMapping(PDO $conn, array $headers, int $analystId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$headers` | `array` |  | the file's column names, as returned by `readCsv()` |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Called from:** [api/assets/import_upload.php](/code/files/api/assets/import_upload.php.md)

### run

*public static* - line 298

Import rows. `$mode` of 'preview' does every check and writes nothing.

```php
function run(
    PDO $conn,
    ActorContext $ctx,
    array $rows,
    array $mapping,
    array $opts,
    string $mode = 'preview'
): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$rows` | `array` |  | source rows, each [column name => value] plus '__row' (the line number in the file) |
| `$mapping` | `array` |  | [column name => null \| {target_kind: 'core'\|'field', target_key}] |
| `$opts` | `array` |  | profile-shaped settings; see asset_import_profiles. |
| `$mode` | `string` | `'preview'` | 'live' writes; anything else ('preview') checks every row and writes no asset |

**Returns:** array the run row, with counts.

**Called from:** [api/assets/import_run.php](/code/files/api/assets/import_run.php.md)

### processRow

*private static* - line 406

One row: map it, find it, write it.

```php
function processRow(
    PDO $conn,
    ActorContext $ctx,
    array $row,
    array $mapping,
    array $opts,
    array $matchKeys,
    ?int $tenantId,
    bool $live
): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$row` | `array` |  | one source row, [column name => value] plus '__row' |
| `$mapping` | `array` |  | [column name => null \| {target_kind: 'core'\|'field', target_key}] |
| `$opts` | `array` |  | the run's profile-shaped settings (defaults, write_mode, on_unknown_option...) |
| `$matchKeys` | `array` |  | the asset columns to identify a row by, in order, e.g. ['hostname', 'service_tag'] |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$live` | `bool` |  | true to write, false for a preview |

**Returns:** array ['action', 'asset_id'?, 'source_ref'?, 'display_name'?, 'detail'?]

### resolveLookups

*private static* - line 470

Turn the NAMES a spreadsheet contains into the ids the columns hold.

🔑 Nobody's export has FreeITSM's internal ids in it. Without this, the
single most obvious mapping anybody would make — a "Type" column full of
the words Printer, Monitor, Headset — fails on every row with
"Unknown asset type id: Printer", which reads as a bug rather than as
something the file is missing.

⚠️ An unknown NAME is an error, never an auto-create. Otherwise the first
typo in a spreadsheet quietly invents an asset type called "Televsion"
and nobody notices until the type list is a mess. It goes to the holding
area instead, which is the whole point of having one.

A numeric value is still accepted as an id, so an export from another
FreeITSM keeps working.

```php
function resolveLookups(PDO $conn, array $core, ?int $tenantId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$core` | `array` |  | [core column => value] from `applyMapping()`; names in lookup columns become ids |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

### hasTenantColumn

*private static* - line 534

Not every lookup table is company-scoped (suppliers may not be).

```php
function hasTenantColumn(PDO $conn, string $table): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$table` | `string` |  | a lookup table name from the fixed list in `resolveLookups()` - never user input |

### applyMapping

*private static* - line 554

Split a source row into core columns and custom field values.

```php
function applyMapping(array $row, array $mapping): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$row` | `array` |  | one source row, [column name => value] |
| `$mapping` | `array` |  | [column name => null \| {target_kind: 'core'\|'field', target_key}] |

### findMatch

*private static* - line 585

Find the existing asset for this row, trying each match key IN ORDER.

🔑 The first key that yields exactly one match wins. A key that yields
MANY stops the search and reports the conflict — falling through to the
next key would quietly resolve an ambiguity nobody had looked at.

```php
function findMatch(PDO $conn, array $core, array $matchKeys, ?int $tenantId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$core` | `array` |  | the row's [core column => value] after lookups |
| `$matchKeys` | `array` |  | the asset columns to try, in order - each from MATCH_KEYS, so safe to put in SQL |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 595 | `AssetImportService::findMatch` | read [assets](/code/tables/assets.md) |  |

### applyCreate

*private static* - line 619

Creates an asset from one import row, with its custom fields, or describes it on a preview.

Throws when nothing is mapped to the asset name.

```php
function applyCreate(
    PDO $conn,
    ActorContext $ctx,
    array $core,
    array $fields,
    array $opts,
    string $ref,
    ?string $name,
    ?int $tenantId,
    bool $live
): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$core` | `array` |  | the row's [core column => value] after lookups and defaults |
| `$fields` | `array` |  | the row's [custom field_key => value] |
| `$opts` | `array` |  | the run's settings (source_name, apply_field_set_id, on_unknown_option...) |
| `$ref` | `string` |  | the value the row is identified by, for the log |
| `$name` | `?string` |  | the asset name for the log |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$live` | `bool` |  | true to create, false to describe it for a preview |

### applyUpdate

*private static* - line 656

Updates an existing asset from one import row, or describes it on a preview.

Works out the changes first so a preview and a live run report the same thing; in
fill mode only empty values are written, and the name is never changed.

```php
function applyUpdate(
    PDO $conn,
    ActorContext $ctx,
    int $assetId,
    array $core,
    array $fields,
    array $opts,
    string $ref,
    ?string $name,
    bool $live
): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$assetId` | `int` |  | The id of the asset. *([standard](/handbook/standard-parameters.md))* |
| `$core` | `array` |  | the row's [core column => value] after lookups and defaults |
| `$fields` | `array` |  | the row's [custom field_key => value] |
| `$opts` | `array` |  | the run's settings; write_mode 'fill' (default) only fills blanks, anything else overwrites |
| `$ref` | `string` |  | the value the row is identified by, for the log |
| `$name` | `?string` |  | the asset name for the log |
| `$live` | `bool` |  | true to write, false to describe the changes for a preview |

### writeFields

*private static* - line 727

Write the custom fields, honouring on_unknown_option.

A field the asset does not carry is REPORTED, not silently dropped — a
column mapped to a field that the asset's type does not have is a mapping
mistake somebody needs to know about.

```php
function writeFields(PDO $conn, ActorContext $ctx, int $assetId, array $fields, array $opts): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$assetId` | `int` |  | The id of the asset. *([standard](/handbook/standard-parameters.md))* |
| `$fields` | `array` |  | [custom field_key => value] to write |
| `$opts` | `array` |  | the run's settings; on_unknown_option decides unknown dropdown values |

### resolveOption

*private static* - line 770

A dropdown value the option list does not have (§6.7). reject -> the row fails; add -> the option is created.

```php
function resolveOption(PDO $conn, array $def, string $value, array $opts): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$def` | `array` |  | the dropdown field's definition, with id and label |
| `$value` | `string` |  | the value from the source row |
| `$opts` | `array` |  | the run's settings; on_unknown_option 'add' creates the option, anything else ('reject') throws |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 772 | `AssetImportService::resolveOption` | read [asset_field_options](/code/tables/asset_field_options.md) |  |
| 781 | `AssetImportService::resolveOption` | **insert** [asset_field_options](/code/tables/asset_field_options.md) (`field_id`, `option_value`, `display_order`) |  |

### attachProfileSet

*private static* - line 795

The profile's field set, applied to everything it touches.

```php
function attachProfileSet(PDO $conn, ActorContext $ctx, int $assetId, array $opts): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$assetId` | `int` |  | The id of the asset. *([standard](/handbook/standard-parameters.md))* |
| `$opts` | `array` |  | the run's settings; apply_field_set_id names the set to attach, if any |

### handleMissing

*private static* - line 815

Rows present last run and absent now (§6.6).

⚠️ Only ever compares against assets THIS PROFILE has touched before.
Comparing against the whole estate would deactivate every asset the
spreadsheet was never about.

```php
function handleMissing(
    PDO $conn,
    int $runId,
    int $profileId,
    array $touchedIds,
    string $policy,
    ?int $tenantId
): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$runId` | `int` |  | the asset_import_runs row being finished |
| `$profileId` | `int` |  | the import profile whose earlier live runs are compared |
| `$touchedIds` | `array` |  | the asset ids this run created, updated or found unchanged |
| `$policy` | `string` |  | on_missing: 'deactivate' sets an inactive status, anything else only logs it |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 819 | `AssetImportService::handleMissing` | read [asset_import_run_entries](/code/tables/asset_import_run_entries.md), read [asset_import_runs](/code/tables/asset_import_runs.md) |  |
| 841 | `AssetImportService::handleMissing` | **update** [assets](/code/tables/assets.md) (`asset_status_id`) |  |

### inactiveStatusId

*private static* - line 861

The asset status to give an asset that has dropped out of the source - the first active status whose name contains retired, disposed or inactive.

```php
function inactiveStatusId(PDO $conn, ?int $tenantId): ?int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 864 | `AssetImportService::inactiveStatusId` | read [asset_status_types](/code/tables/asset_status_types.md) |  |

### unresolved

*public static* - line 884

Rows that could not be imported and have not been dealt with.

```php
function unresolved(PDO $conn, ?int $runId = null, int $limit = 200): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$runId` | `?int` | `null` | only this run's parked rows, or null for every live run |
| `$limit` | `int` | `200` | the most rows to return |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 889 | `AssetImportService::unresolved` | read [asset_import_run_entries](/code/tables/asset_import_run_entries.md), read [asset_import_runs](/code/tables/asset_import_runs.md) |  |

**Called from:** [api/assets/import_history.php](/code/files/api/assets/import_history.php.md)

### resolveEntry

*public static* - line 916

Mark a parked row dealt with — either retried successfully, or dismissed.

```php
function resolveEntry(PDO $conn, int $entryId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$entryId` | `int` |  | the asset_import_run_entries row to mark dealt with |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 918 | `AssetImportService::resolveEntry` | **update** [asset_import_run_entries](/code/tables/asset_import_run_entries.md) (`resolved_datetime`) |  |

**Called from:** [api/assets/import_resolve.php](/code/files/api/assets/import_resolve.php.md)

### startRun

*private static* - line 932

Records the start of an import run and returns its id.

```php
function startRun(PDO $conn, ActorContext $ctx, array $opts, string $mode, ?int $tenantId): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$opts` | `array` |  | the run's settings; profile_id, source_name and stored_file are recorded |
| `$mode` | `string` |  | 'live' or 'preview' (anything not 'live' is stored as preview) |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 940 | `AssetImportService::startRun` | **insert** [asset_import_runs](/code/tables/asset_import_runs.md) (`profile_id`, `mode`, `status`, `source_name`, `stored_file`, `triggered_by_analyst_id`, `tenant_id`, `started_datetime`) |  |

### finishRun

*private static* - line 962

Marks an import run finished with its counts, and returns the run.

```php
function finishRun(PDO $conn, int $runId, int $seen, array $counts): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$runId` | `int` |  | the asset_import_runs row to finish |
| `$seen` | `int` |  | how many source rows were read |
| `$counts` | `array` |  | [action => count] for every ACTIONS key |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 965 | `AssetImportService::finishRun` | **update** [asset_import_runs](/code/tables/asset_import_runs.md) (`status`, `finished_datetime`, `seen_count`, `created_count`, `updated_count`, `unchanged_count`, `conflict_count`, `skipped_count`, `error_count`) |  |

### loadRun

*public static* - line 983

Loads one import run with its counts as integers; throws not found when it does not exist.

```php
function loadRun(PDO $conn, int $runId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$runId` | `int` |  | the asset_import_runs row to load |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 985 | `AssetImportService::loadRun` | read [asset_import_runs](/code/tables/asset_import_runs.md) |  |

**Called from:** [api/assets/import_history.php](/code/files/api/assets/import_history.php.md)

### runEntries

*public static* - line 1005

Every row's outcome for a run.

```php
function runEntries(PDO $conn, int $runId, ?string $action = null, int $limit = 500): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$runId` | `int` |  | the asset_import_runs row whose log to read |
| `$action` | `?string` | `null` | only entries with this ACTIONS value, or null for all |
| `$limit` | `int` | `500` | the most entries to return |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1007 | `AssetImportService::runEntries` | read [asset_import_run_entries](/code/tables/asset_import_run_entries.md) |  |

**Called from:** [api/assets/import_history.php](/code/files/api/assets/import_history.php.md), [api/assets/import_run.php](/code/files/api/assets/import_run.php.md)

### logEntry

*private static* - line 1030

Writes one row of an import run's log.

```php
function logEntry(
    PDO $conn,
    int $runId,
    ?int $rowNo,
    string $action,
    ?int $assetId,
    ?string $ref,
    ?string $name,
    ?string $detail,
    ?array $raw
): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$runId` | `int` |  | the asset_import_runs row the entry belongs to |
| `$rowNo` | `?int` |  | the source line number; null for an entry with no source row (a missing asset) |
| `$action` | `string` |  | one of ACTIONS |
| `$assetId` | `?int` |  | The id of the asset. *([standard](/handbook/standard-parameters.md))* |
| `$ref` | `?string` |  | the value the row is identified by (cut to 255 characters) |
| `$name` | `?string` |  | the asset name (cut to 255 characters) |
| `$detail` | `?string` |  | what happened, in words (cut to 1000 characters) |
| `$raw` | `?array` |  | the source row, kept so it can be corrected and retried; '__row' is dropped |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1039 | `AssetImportService::logEntry` | **insert** [asset_import_run_entries](/code/tables/asset_import_run_entries.md) (`run_id`, `row_number`, `action`, `asset_id`, `source_ref`, `display_name`, `detail`, `raw_row`) |  |

### loadCore

*private static* - line 1060

An asset's id and core columns, for comparing with an import row.

```php
function loadCore(PDO $conn, int $assetId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$assetId` | `int` |  | The id of the asset. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1063 | `AssetImportService::loadCore` | read [assets](/code/tables/assets.md) |  |

### firstNonEmpty

*private static* - line 1074

The first of `$keys` that has a non-blank value in `$core`, or null.

```php
function firstNonEmpty(array $core, array $keys): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$core` | `array` |  | [core column => value] |
| `$keys` | `array` |  | the columns to try, in order |

### refOf

*private static* - line 1091

The value an import row is matched on - the first non-blank of the match keys.

```php
function refOf(array $row, array $mapping, array $matchKeys): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$row` | `array` |  | one source row, [column name => value] |
| `$mapping` | `array` |  | [column name => null \| {target_kind, target_key}] |
| `$matchKeys` | `array` |  | the asset columns a row is identified by, in order |

### nameOf

*private static* - line 1103

The asset name an import row maps to.

```php
function nameOf(array $row, array $mapping): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$row` | `array` |  | one source row, [column name => value] |
| `$mapping` | `array` |  | [column name => null \| {target_kind, target_key}] |

### describe

*private static* - line 1115

A human sentence describing what a row did, for the log.

```php
function describe(array $core, array $fields): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$core` | `array` |  | [core column => value] written or to be written |
| `$fields` | `array` |  | [custom field_key => value], possibly with '__ignored' listing keys that went nowhere |

### short

*private static* - line 1140

A value cut to 40 characters for the run log.

```php
function short($v): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` |  |  | the value to show |


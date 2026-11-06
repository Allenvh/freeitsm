# cardDavSyncRun()

Run an import for one CardDAV provider.

Mirrors `directorySyncRun()` and shares its bookkeeping table, its counts and
every one of its safety rules — including preview, which runs this identical
code path and writes nothing to `users`.

```php
function cardDavSyncRun(
    PDO $conn,
    array $provider,
    string $mode = 'live',
    ?int $analystId = null
): array
```

Defined in [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md), line 349.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$provider` | `array` |  | the auth_providers row (protocol 'carddav') as stored, password still encrypted |
| `$mode` | `string` | `'live'` | 'live' \| 'preview' |
| `$analystId` | `?int` | `null` | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 355 | `cardDavSyncRun` | **insert** [directory_sync_runs](/code/tables/directory_sync_runs.md) (`provider_id`, `mode`, `status`, `started_datetime`, `triggered_by_analyst_id`) |  |
| 495 | `cardDavSyncRun` | **update** [auth_providers](/code/tables/auth_providers.md) (`sync_last_run_datetime`, `sync_last_count`) |  |

## Calls

[cardDavConfigFromProvider()](/code/functions/cardDavConfigFromProvider.md), [cardDavFetchCards()](/code/functions/cardDavFetchCards.md), [cardDavScopeList()](/code/functions/cardDavScopeList.md), [cdsyncInScope()](/code/functions/cdsyncInScope.md), [cdsyncMapCard()](/code/functions/cdsyncMapCard.md), [cdsyncResolveScope()](/code/functions/cdsyncResolveScope.md), [dsyncApplyToExisting()](/code/functions/dsyncApplyToExisting.md), [dsyncCreate()](/code/functions/dsyncCreate.md), [dsyncFindExisting()](/code/functions/dsyncFindExisting.md), [dsyncFinishRun()](/code/functions/dsyncFinishRun.md), [dsyncGetRun()](/code/functions/dsyncGetRun.md), [dsyncHandleMissing()](/code/functions/dsyncHandleMissing.md), [dsyncLogEntry()](/code/functions/dsyncLogEntry.md), [dsyncNewPersonSummary()](/code/functions/dsyncNewPersonSummary.md), [syncBrakeTripped()](/code/functions/syncBrakeTripped.md)

## Called from

- [api/system/run_directory_sync.php](/code/files/api/system/run_directory_sync.php.md)


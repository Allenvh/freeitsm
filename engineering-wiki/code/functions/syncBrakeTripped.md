# syncBrakeTripped()

Has the directory returned so much less than last time that we should refuse to act on it?

THE MOST IMPORTANT FUNCTION IN THIS FILE. Without it, one wrong character in a
base DN deactivates every person in a company, and the sync reports success
while doing it. With it, the run stops and says why.

A first run (no baseline) is never braked — there is nothing to compare with,
and refusing to import anybody the first time would make the feature unusable.

```php
function syncBrakeTripped(array $provider, int $seen, ?array $words = null): ?string
```

Defined in [includes/directory_sync.php](/code/files/includes/directory_sync.php.md), line 480.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$provider` | `array` |  | the auth_providers row - sync_brake_percent and sync_last_count are read |
| `$seen` | `int` |  | how many people this run found |
| `$words` | `?array` | `null` | optional wording for the message: source (e.g. 'address book') and causes; null keeps the LDAP wording |

**Returns:** `?string`

## Called from

- [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md) - in [cardDavSyncRun()](/code/functions/cardDavSyncRun.md)
- [includes/directory_sync.php](/code/files/includes/directory_sync.php.md) - in [directorySyncRun()](/code/functions/directorySyncRun.md)


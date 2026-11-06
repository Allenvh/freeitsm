# cardDavConfigFromProvider()

Build the config array a request needs from an `auth_providers` row.

⚠️ Decrypts here and nowhere earlier: the row travels around as stored, and
the plaintext exists only for the length of one call. Same handling as
`ldap_bind_password`.

```php
function cardDavConfigFromProvider(array $provider, ?string $overridePassword = null): array
```

Defined in [includes/carddav.php](/code/files/includes/carddav.php.md), line 623.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$provider` | `array` |  | an auth_providers row as stored, carddav_password still encrypted |
| `$overridePassword` | `?string` | `null` | a plain password to use instead of the stored one; null decrypts the stored one |

**Returns:** `array`

## Calls

[decryptValue()](/code/functions/decryptValue.md)

## Called from

- [api/system/debug-tools/D015_carddav_health.php](/code/files/api/system/debug-tools/D015_carddav_health.php.md)
- [api/system/debug-tools/D016_carddav_drift.php](/code/files/api/system/debug-tools/D016_carddav_drift.php.md)
- [includes/carddav_create.php](/code/files/includes/carddav_create.php.md) - in [cardDavCreateContact()](/code/functions/cardDavCreateContact.md)
- [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md) - in [cardDavSyncRun()](/code/functions/cardDavSyncRun.md)
- [includes/carddav_write.php](/code/files/includes/carddav_write.php.md) - in [cardDavPushPersonChanges()](/code/functions/cardDavPushPersonChanges.md)


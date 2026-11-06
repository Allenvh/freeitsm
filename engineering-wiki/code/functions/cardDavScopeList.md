# cardDavScopeList()

Read `carddav_scope_value` into a list.

Newline-separated in the column, same as `sync_ou_includes`. Deduplicated
case-insensitively but stored as typed, because a CATEGORIES value is
matched case-insensitively by every client that writes one, while a group UID
is opaque and must survive untouched.

```php
function cardDavScopeList($stored): array
```

Defined in [includes/carddav.php](/code/files/includes/carddav.php.md), line 600.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$stored` |  |  | auth_providers.carddav_scope_value as stored - newline-separated; null reads as empty |

**Returns:** `array`

## Called from

- [includes/carddav_create.php](/code/files/includes/carddav_create.php.md) - in [cardDavCreatePreview()](/code/functions/cardDavCreatePreview.md), [cardDavCreateContact()](/code/functions/cardDavCreateContact.md)
- [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md) - in [cardDavSyncRun()](/code/functions/cardDavSyncRun.md)
- [system/sso/carddav.php](/code/files/system/sso/carddav.php.md)


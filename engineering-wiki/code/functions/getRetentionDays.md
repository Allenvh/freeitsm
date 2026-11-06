# getRetentionDays()

Returns how many days an article stays in the recycle bin before it is purged - the knowledge_recycle_bin_days setting, 30 when unset, 0 meaning forever.

```php
function getRetentionDays($conn)
```

Defined in [api/knowledge/knowledge_archive.php](/code/files/api/knowledge/knowledge_archive.php.md), line 152.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 153 | `getRetentionDays` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [api/knowledge/knowledge_archive.php](/code/files/api/knowledge/knowledge_archive.php.md) - in [handleList()](/code/functions/handleList.md), [purgeExpired()](/code/functions/purgeExpired.md)


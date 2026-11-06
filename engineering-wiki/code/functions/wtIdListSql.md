# wtIdListSql()

A safe `IN (…)` fragment from a list of ints. '(0)' when empty, so it matches nothing.

```php
function wtIdListSql(array $ids): string
```

Defined in [includes/watchtower_settings.php](/code/files/includes/watchtower_settings.php.md), line 373.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ids` | `array` |  | the ids to list; cast to int, so untrusted values are safe |

**Returns:** `string`

## Called from

- [api/watchtower/save_settings.php](/code/files/api/watchtower/save_settings.php.md)
- [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) - in [getWatchtowerData()](/code/functions/getWatchtowerData.md)


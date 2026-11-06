# recentTrailWithinGap()

Are two visits close enough in time to belong to the same sitting?

```php
function recentTrailWithinGap(string $earlier, string $later): bool
```

Defined in [includes/recent_trail.php](/code/files/includes/recent_trail.php.md), line 370.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$earlier` | `string` |  | the earlier visit time, a UTC 'Y-m-d H:i:s' string |
| `$later` | `string` |  | the later visit time, a UTC 'Y-m-d H:i:s' string |

**Returns:** `bool`

## Called from

- [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) - in [entityRecentTrail()](/code/functions/entityRecentTrail.md)


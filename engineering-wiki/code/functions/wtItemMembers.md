# wtItemMembers()

The chosen members for an item, or NULL meaning "not customised, use the built-in default". An empty ARRAY is a real answer — the admin chose nothing — and is not the same as null.

Ids are checked against the live lookup table, so a status deleted after being
selected drops out instead of being counted as a member that no longer exists.

```php
function wtItemMembers(PDO $conn, string $itemKey): ?array
```

Defined in [includes/watchtower_settings.php](/code/files/includes/watchtower_settings.php.md), line 344.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$itemKey` | `string` |  | a key of `wtSelectableItems()`, e.g. 'tickets.by_status'; an unknown key gives null |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 351 | `wtItemMembers` | read [watchtower_items](/code/tables/watchtower_items.md) |  |
| 356 | `wtItemMembers` | read [watchtower_item_members](/code/tables/watchtower_item_members.md) |  |

## Calls

[wtSelectableItems()](/code/functions/wtSelectableItems.md)

## Called from

- [api/watchtower/get_settings.php](/code/files/api/watchtower/get_settings.php.md)
- [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) - in [getWatchtowerData()](/code/functions/getWatchtowerData.md)


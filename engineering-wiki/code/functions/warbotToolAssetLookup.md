# warbotToolAssetLookup()

asset_lookup: up to eight assets whose hostname, asset tag or service tag contains the query, with type, status, OS, location, last user and last seen.

```php
function warbotToolAssetLookup(PDO $conn, array $args, int $analystId): string
```

Defined in [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md), line 562.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$args` | `array` |  | the model's arguments - untrusted: query (part of a hostname, asset tag or service tag) |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 569 | `warbotToolAssetLookup` | read [assets](/code/tables/assets.md), read [asset_types](/code/tables/asset_types.md), read [asset_status_types](/code/tables/asset_status_types.md), read [asset_locations](/code/tables/asset_locations.md) |  |

*Nothing calls this function by name.*


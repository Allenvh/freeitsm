# assetPublicBaseUrl()

The install's public base, including any sub-folder - see `assetLabelUrl()`.

```php
function assetPublicBaseUrl(?PDO $conn = null): string
```

Defined in [includes/asset_labels.php](/code/files/includes/asset_labels.php.md), line 171.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `?PDO` | `null` | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Calls

[connectToDatabase()](/code/functions/connectToDatabase.md), [publicBaseUrl()](/code/functions/publicBaseUrl.md)

*Nothing calls this function by name.*


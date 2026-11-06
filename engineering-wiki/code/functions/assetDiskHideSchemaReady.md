# assetDiskHideSchemaReady()

Has Database Verification created the table yet? Everything here answers "nothing is hidden" when it has not, so an install that has pulled the update and not yet verified shows its drives rather than an error.

```php
function assetDiskHideSchemaReady(PDO $conn): bool
```

Defined in [includes/asset_disk_visibility.php](/code/files/includes/asset_disk_visibility.php.md), line 40.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Called from

- [api/assets/save_disk_hide_rule.php](/code/files/api/assets/save_disk_hide_rule.php.md)
- [includes/asset_disk_visibility.php](/code/files/includes/asset_disk_visibility.php.md) - in [assetDiskHideRulesFor()](/code/functions/assetDiskHideRulesFor.md)


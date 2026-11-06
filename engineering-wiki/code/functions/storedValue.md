# storedValue()

Describe what is actually stored, keeping the three "blank" cases apart: the column is absent, the value is NULL, or the value is an empty string.

```php
function storedValue(array $row, string $col, array $apCols, bool $maskIt = false)
```

Defined in [api/system/debug-tools/D011_directory_sync_readiness.php](/code/files/api/system/debug-tools/D011_directory_sync_readiness.php.md), line 318.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$row` | `array` |  | the auth_providers row as read back |
| `$col` | `string` |  | the column to describe |
| `$apCols` | `array` |  | the columns auth_providers really has, keyed by lower-case name |
| `$maskIt` | `bool` | `false` | true to mask the value with `mask()` rather than print it |

## Calls

[mask()](/code/functions/mask.md)

## Called from

- [api/system/debug-tools/D011_directory_sync_readiness.php](/code/files/api/system/debug-tools/D011_directory_sync_readiness.php.md)


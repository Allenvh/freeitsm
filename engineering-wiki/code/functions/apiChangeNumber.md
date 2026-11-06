# apiChangeNumber()

The display reference of a change, such as CHG-0042.

```php
function apiChangeNumber(int $id): string
```

Defined in [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md), line 48.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$id` | `int` |  | the changes row id |

**Returns:** `string`

## Called from

- [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md) - in [apiSerializeChange()](/code/functions/apiSerializeChange.md)


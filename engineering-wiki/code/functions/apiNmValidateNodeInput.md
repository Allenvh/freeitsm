# apiNmValidateNodeInput()

Validate + normalise one node input row. Returns [cmdb_object_id, x|null, y|null, size, icon_override, ref] — null x/y means auto-place.

```php
function apiNmValidateNodeInput(PDO $conn, array $n, int $index, ?array $apiKey = null): array
```

Defined in [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md), line 452.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$n` | `array` |  | one entry of the caller's nodes list (untrusted) - cmdb_object_id, x, y, size, icon_override, ref |
| `$index` | `int` |  | its position in that list, quoted in 422 messages |
| `$apiKey` | `?array` | `null` | the calling API key; null skips the company check on the object |

**Returns:** `array`

## Calls

[apiError()](/code/functions/apiError.md), [apiNmValidateIcon()](/code/functions/apiNmValidateIcon.md), [apiNmValidateObjectExists()](/code/functions/apiNmValidateObjectExists.md), [apiNmValidateSize()](/code/functions/apiNmValidateSize.md)

## Called from

- [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) - in [apiNmNodesCreate()](/code/functions/apiNmNodesCreate.md)


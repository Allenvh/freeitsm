# apiNmValidateLineStyle()

Returns the connector line style when it is a known one, otherwise answers 422 listing the valid ones.

```php
function apiNmValidateLineStyle(string $style): string
```

Defined in [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md), line 356.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$style` | `string` |  | the requested connector line style - solid or dashed |

**Returns:** `string`

## Calls

[apiError()](/code/functions/apiError.md)

## Called from

- [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) - in [apiNmConnectorsCreate()](/code/functions/apiNmConnectorsCreate.md), [apiNmConnectorsUpdate()](/code/functions/apiNmConnectorsUpdate.md)


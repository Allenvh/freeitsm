# domainSettingValidate()

Validate and normalise one incoming value. Returns the value to store, or throws InvalidArgumentException with a message fit to show the operator.

```php
function domainSettingValidate(string $key, $raw): string
```

Defined in [includes/domains/settings.php](/code/files/includes/domains/settings.php.md), line 193.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` | `string` |  | a key from `domainSettingDefinitions()`; unknown throws |
| `$raw` |  |  | the submitted value (untrusted) - a string, number or bool |

**Returns:** `string`

## Calls

[domainParseDays()](/code/functions/domainParseDays.md), [domainSettingDefinitions()](/code/functions/domainSettingDefinitions.md)

## Called from

- [api/domains/settings.php](/code/files/api/domains/settings.php.md)


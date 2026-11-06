# domainSettingDefinitions()

key => [default, validator, tab]. The tab decides which capability may write the key; the validator is one of the names `domainSettingValidate()` understands.

```php
function domainSettingDefinitions(): array
```

Defined in [includes/domains/settings.php](/code/files/includes/domains/settings.php.md), line 29.

**Returns:** `array`

## Called from

- [api/domains/settings.php](/code/files/api/domains/settings.php.md)
- [includes/domains/settings.php](/code/files/includes/domains/settings.php.md) - in [domainSettings()](/code/functions/domainSettings.md), [domainSettingValidate()](/code/functions/domainSettingValidate.md)


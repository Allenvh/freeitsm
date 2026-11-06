# configRequirements()

Returns every constant config.php is expected to define, keyed by name, with its kind, where it lives, any fallback, what happens without it and the line to add.

```php
function configRequirements(): array
```

Defined in [includes/config_requirements.php](/code/files/includes/config_requirements.php.md), line 30.

**Returns:** `array`

## Called from

- [api/system/debug-tools/D017_config_completeness.php](/code/files/api/system/debug-tools/D017_config_completeness.php.md)
- [includes/config_requirements.php](/code/files/includes/config_requirements.php.md) - in [configRequirementsReport()](/code/functions/configRequirementsReport.md)


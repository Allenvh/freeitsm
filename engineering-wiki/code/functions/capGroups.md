# capGroups()

The registry shaped for the System -> Roles picker: grouped by module, labelled, with the umbrella first so "manage everything here" reads as the headline choice.

Generated from `capRegistry()`, so adding a constant + a registry entry makes the
tick-box appear. There is no second list to keep in step.

```php
function capGroups(): array
```

Defined in [includes/capabilities.php](/code/files/includes/capabilities.php.md), line 547.

**Returns:** `array`

## Calls

[capIsSensitive()](/code/functions/capIsSensitive.md), [capLabel()](/code/functions/capLabel.md), [capModules()](/code/functions/capModules.md), [capsForModule()](/code/functions/capsForModule.md), [capUmbrella()](/code/functions/capUmbrella.md)

## Called from

- [system/roles/index.php](/code/files/system/roles/index.php.md)


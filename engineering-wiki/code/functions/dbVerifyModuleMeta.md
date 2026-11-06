# dbVerifyModuleMeta()

Metadata for every known module group: key => {label, color, colorHover}. The front end filters this down to the groups that actually have a card.

```php
function dbVerifyModuleMeta(): array
```

Defined in [includes/db_verify_modules.php](/code/files/includes/db_verify_modules.php.md), line 175.

**Returns:** `array`

## Calls

[dbVerifyModuleLabels()](/code/functions/dbVerifyModuleLabels.md), [getModuleColors()](/code/functions/getModuleColors.md)

## Called from

- [api/system/db_verify.php](/code/files/api/system/db_verify.php.md)


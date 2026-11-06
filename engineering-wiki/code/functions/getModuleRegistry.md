# getModuleRegistry()

Canonical list of user-facing modules for access control (issue #30), key => name. System is deliberately excluded — it's gated by is_admin, not module access. Keys match the waffle launcher / landing page module keys.

```php
function getModuleRegistry(): array
```

Defined in [includes/functions.php](/code/files/includes/functions.php.md), line 79.

**Returns:** `array`

## Calls

[t()](/code/functions/t.md)

## Called from

- [api/system/get_effective_module_access.php](/code/files/api/system/get_effective_module_access.php.md)
- [api/system/get_module_access.php](/code/files/api/system/get_module_access.php.md)
- [api/system/save_module_grants.php](/code/files/api/system/save_module_grants.php.md)
- [includes/report_packs/blocks.php](/code/files/includes/report_packs/blocks.php.md) - in [rpModuleName()](/code/functions/rpModuleName.md)
- [includes/services/analysts.php](/code/files/includes/services/analysts.php.md) - in `AnalystsService::moduleKeys`


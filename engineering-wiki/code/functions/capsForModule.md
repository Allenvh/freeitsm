# capsForModule()

Every capability belonging to a module. Enum twin: Capability::forModule().

```php
function capsForModule(string $module): array
```

Defined in [includes/capabilities.php](/code/files/includes/capabilities.php.md), line 494.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$module` | `string` |  | a Layer 1 module slug, e.g. 'assets' |

**Returns:** `array`

## Calls

[capRegistry()](/code/functions/capRegistry.md)

## Called from

- [includes/capabilities.php](/code/files/includes/capabilities.php.md) - in [capExpandUmbrellas()](/code/functions/capExpandUmbrellas.md), [capGroups()](/code/functions/capGroups.md), [capSelfCheck()](/code/functions/capSelfCheck.md)


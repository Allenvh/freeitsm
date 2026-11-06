# configRequirementsReport()

Compare the running state and the operator's config.php with the list. Each row: name, kind, defined (bool), inConfig (bool), status ('ok' | 'default' | 'problem'). Names and booleans only.

```php
function configRequirementsReport(string $configPath): array
```

Defined in [includes/config_requirements.php](/code/files/includes/config_requirements.php.md), line 125.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$configPath` | `string` |  | filesystem path of the operator's config.php |

**Returns:** `array`

## Calls

[configFileShape()](/code/functions/configFileShape.md), [configRequirements()](/code/functions/configRequirements.md)

## Called from

- [api/system/debug-tools/D017_config_completeness.php](/code/files/api/system/debug-tools/D017_config_completeness.php.md)


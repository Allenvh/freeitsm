# configFileShape()

What a config file DECLARES, read with the tokeniser so commented-out lines do not count. Returns names only: the constants it `define()`s and the functions it declares. Never values.

```php
function configFileShape(string $path): array
```

Defined in [includes/config_requirements.php](/code/files/includes/config_requirements.php.md), line 89.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$path` | `string` |  | filesystem path of the PHP config file to read; unreadable gives readable => false |

**Returns:** `array`

## Called from

- [includes/config_requirements.php](/code/files/includes/config_requirements.php.md) - in [configRequirementsReport()](/code/functions/configRequirementsReport.md)


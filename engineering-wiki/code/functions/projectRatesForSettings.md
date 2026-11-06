# projectRatesForSettings()

Default and analyst rates with their dates, newest first - Budget tab only.

```php
function projectRatesForSettings(PDO $conn): array
```

Defined in [api/projects/settings.php](/code/files/api/projects/settings.php.md), line 40.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 43 | `projectRatesForSettings` | read [project_labour_rates](/code/tables/project_labour_rates.md), read [analysts](/code/tables/analysts.md) |  |

## Called from

- [api/projects/settings.php](/code/files/api/projects/settings.php.md)


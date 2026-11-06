# lmsCtSettings()

The settings, defaults applied.

```php
function lmsCtSettings(PDO $conn): array
```

Defined in [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md), line 53.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 65 | `lmsCtSettings` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [api/lms/tests.php](/code/files/api/lms/tests.php.md)
- [lms/test.php](/code/files/lms/test.php.md)

